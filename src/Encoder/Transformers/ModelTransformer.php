<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Relations\Relation;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;
use Pixelindustries\JsonApi\Exceptions\EncodingException;
use Pixelindustries\JsonApi\Support\Resource\RelationData;
use RuntimeException;
use UnexpectedValueException;

class ModelTransformer extends AbstractTransformer
{
    const ATTRIBUTES_KEY    = 'attributes';
    const DATA_KEY          = 'data';
    const LINKS_KEY         = 'links';
    const LINKS_SELF_KEY    = 'self';
    const LINKS_RELATED_KEY = 'related';
    const RELATIONSHIPS_KEY = 'relationships';


    /**
     * Transforms given data.
     *
     * @param Model $model
     * @return array
     * @throws EncodingException
     */
    public function transform($model)
    {
        if ( ! ($model instanceof Model)) {
            throw new UnexpectedValueException("ModelTransformer expects Eloquent model instance");
        }

        if ( ! ($resource = $this->getResourceForModel($model))) {
            throw new EncodingException("Could not determine resource for '" . get_class($model) . "'");
        }

        $resource->setModel($model);

        $data = [
            'id'                      => $resource->id(),
            'type'                    => $resource->type(),
            static::ATTRIBUTES_KEY    => $this->serializeAttributes($resource),
            static::RELATIONSHIPS_KEY => $this->processRelationships($resource),
        ];

        if ( ! count($data[static::ATTRIBUTES_KEY])) {
            unset($data[static::ATTRIBUTES_KEY]);
        }

        if ( ! count($data[static::RELATIONSHIPS_KEY])) {
            unset($data[static::RELATIONSHIPS_KEY]);
        }

        return $data;
    }

    /**
     * Returns resource for given model instance.
     *
     * @param Model $model
     * @return null|ResourceInterface
     */
    protected function getResourceForModel(Model $model)
    {
        return $this->encoder->getResourceForModel($model);
    }

    /**
     * Returns base URI for the resource.
     *
     * @param ResourceInterface $resource
     * @return string
     */
    protected function getBaseResourceUrl(ResourceInterface $resource)
    {
        return $this->encoder->getBaseUrl() . '/' . $resource->type();
    }

    /**
     * Returns serialized attributes for a given resource.
     *
     * @param ResourceInterface $resource
     * @return array
     */
    protected function serializeAttributes(ResourceInterface $resource)
    {
        $data = [];

        foreach ($resource->availableAttributes() as $key) {
            $data[ $key ] = $resource->attributeValue($key);
        }

        return $data;
    }

    /**
     * Processes and serializes relationships for a given resource.
     *
     * @param ResourceInterface $resource
     * @return array
     */
    protected function processRelationships(ResourceInterface $resource)
    {
        $requested = array_flip($this->encoder->getRequestedIncludes());
        $default   = array_flip($resource->defaultIncludes());

        $data = [];

        foreach ($resource->availableIncludes() as $key) {

            // Analyze the relationship, determine the JSON-API type
            $relationData = $this->getRelationData($resource, $key);
            $relatedType  = null;

            if ($relationData->model) {

                $relatedResource = $this->encoder->getResourceForModel($relationData->model);

                if ($relatedResource) {
                    $relatedResource->setModel($relationData->model);
                    $relatedType = $relatedResource->type();
                }
            }


            $data[ $key ] = [
                static::LINKS_KEY => [
                    static::LINKS_SELF_KEY => $this->getBaseResourceUrl($resource) . '/relationships/' . $key,
                ],
            ];

            // If the relation is not morph/variable, add the related link
            if ($relatedType) {
                $data[ $key ][ static::LINKS_KEY ][ static::LINKS_RELATED_KEY ] = $this->getBaseResourceUrl($resource)
                    . '/' . $relatedType;
            }


            $fullyIncluded = array_key_exists($key, $requested) || array_key_exists($key, $default);

            // References (type/id) should be added as data for the relationship if:
            // a. a relationship is included by default or by the client
            // b. a relationship is marked to always have references included in the resource
            if ($fullyIncluded || $resource->includeReferencesForRelation($key)) {

                // Get nested data, either plucking the keys for the related model,
                // or simply retrieving the entire model/collection.

                if ($fullyIncluded) {

                    // If fully included, also add the information to the encoder.
                    // This data must be transformed using a relevant transformer.

                    // If fully included, get the type/id references from the transformed data
                    // to prevent redundant processing.

                    $related = $this->getRelatedFullData($resource, $relationData);
                    $this->addRelatedDataToEncoder($related, $relationData->singular);

                    $data[ $key ][ static::DATA_KEY ] = $this->getRelatedReferencesFromRelatedData(
                        $related,
                        $relationData->singular
                    );

                } else {

                    $data[ $key ][ static::DATA_KEY ] = $this->getRelatedReferenceData($resource, $relationData);
                }

            }
        }

        return $data;
    }



    /**
     * Returns transformed data for full includes
     *
     * @param ResourceInterface $resource
     * @param RelationData      $relation
     * @return array
     */
    protected function getRelatedFullData(ResourceInterface $resource, RelationData $relation)
    {
        if ($relation->relation instanceof Relations\MorphTo) {
            throw new RuntimeException('Morph not yet implemented!');
        }

        if ( ! $relation->model) {
            throw new UnexpectedValueException("RelationData model key must be set for retrieving references");
        }

        $relatedResource = $this->encoder->getResourceForModel($relation->model);

        if ( ! $relatedResource) {
            throw new RuntimeException("Could not determine resource for model '" . get_class($relation->model) . "'");
        }

        $includeKey = $relation->key;
        $method     = $resource->getRelationMethodForInclude($includeKey);

        $related = $resource->getModel()->{$method};

        $transformer = $this->encoder->makeTransformer($related);

        return [
            static::DATA_KEY => $transformer->transform($related),
        ];
    }

    /**
     * @param ResourceInterface $resource
     * @param RelationData      $relation
     * @return array
     */
    protected function getRelatedReferenceData(ResourceInterface $resource, RelationData $relation)
    {
        if ($relation->relation instanceof Relations\MorphTo) {
            throw new RuntimeException('Morph not yet implemented!');
        }

        if ( ! $relation->model) {
            throw new UnexpectedValueException("RelationData model key must be set for retrieving references");
        }

        $relatedResource = $this->encoder->getResourceForModel($relation->model);

        if ( ! $relatedResource) {
            throw new RuntimeException("Could not determine resource for model '" . get_class($relation->model) . "'");
        }

        $includeKey = $relation->key;
        $keyName    = $relation->model->getKeyName();
        $method     = $resource->getRelationMethodForInclude($includeKey);

        if ($resource->getModel()->relationLoaded($method)) {
            $ids = $resource->{$method}->pluck($keyName)->toArray();
        } else {
            $ids = $resource->includeRelation($includeKey)->pluck($keyName)->toArray();
        }

        return array_map(
            function ($id) use ($relatedResource) {
                return [ 'type' => $relatedResource->type(), 'id' => $id ];
            },
            $ids
        );
    }

    /**
     * Registers related data with encoder for full top level side-loaded includes.
     *
     * @param null|array|array[] $data
     * @param bool               $singular      whether the relation is singular
     */
    protected function addRelatedDataToEncoder($data, $singular = true)
    {
        if (empty($data)) {
            return;
        }

        if ($singular) {
            $data = [ array_get($data, static::DATA_KEY, []) ];
        } else {
            $data = array_get($data, static::DATA_KEY, []);
        }

        foreach ($data as $related) {
            $identifier = array_get($related, 'type') . ':' . array_get($related, 'id');
            $this->encoder->addIncludedData($related, $identifier);
        }
    }

    /**
     * Extracts type/id references from full include data
     *
     * @param array $data
     * @param bool  $singular
     * @return array
     */
    protected function getRelatedReferencesFromRelatedData(array $data, $singular = false)
    {
        $data = array_get($data, static::DATA_KEY, []);

        if ($singular) {
            return [
                'type' => array_get($data, 'type'),
                'id'   => array_get($data, 'id'),
            ];
        }

        return array_map(
            function ($related) {
                return [
                    'type' => array_get($related, 'type'),
                    'id'   => array_get($related, 'id'),
                ];
            },
            $data
        );
    }

    // ------------------------------------------------------------------------------
    //      Analyze Relations
    // ------------------------------------------------------------------------------

    /**
     * Makes relation data for relation key on resource.
     *
     * @param ResourceInterface $resource
     * @param string            $key
     * @return RelationData
     */
    protected function getRelationData(ResourceInterface $resource, $key)
    {
        $relation = $resource->includeRelation($key);
        $variable = $this->isVariableRelation($relation);

        return new RelationData([
            'key'      => $key,
            'variable' => $variable,
            'singular' => $this->isSingularRelation($relation),
            'relation' => $relation,
            'model'    => $variable ? null : $relation->getRelated(),
        ]);
    }

    /**
     * Returns whether given relation is singular.
     *
     * @param Relation $relation
     * @return bool
     */
    protected function isSingularRelation(Relation $relation)
    {
        return  $relation instanceof Relations\BelongsTo
            ||  $relation instanceof Relations\HasOne
            ||  $relation instanceof Relations\MorphOne
            ||  $relation instanceof Relations\MorphTo;
    }

    /**
     * Returns whether given relation is variable (morphed).
     *
     * @param Relation $relation
     * @return bool
     */
    protected function isVariableRelation(Relation $relation)
    {
        return $relation instanceof Relations\MorphTo;
    }

}
