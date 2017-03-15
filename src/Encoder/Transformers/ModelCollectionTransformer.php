<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;
use UnexpectedValueException;

class ModelCollectionTransformer extends ModelTransformer
{

    /**
     * The resource that describes the current set of models.
     *
     * @var ResourceInterface
     */
    protected $resource;


    /**
     * Transforms given data.
     *
     * @param Collection $models
     * @return array
     */
    public function transform($models)
    {
        if ( ! ($models instanceof Collection)) {
            throw new UnexpectedValueException("ModelTransformer expects collection instance with models");
        }

        if ($models->isEmpty()) {
            return [];
        }

        if ($this->isVariable) {
            $this->resource = null;
        } else {
            $this->resource = $this->getResourceForCollection($models);
        }

        $data = [];

        foreach ($models as $model) {

            $data[] = parent::transform($model);
        }

        return $data;
    }

    /**
     * Returns resource that all models in a collection are expected to share.
     *
     * @param Collection $models
     * @return null|ResourceInterface
     */
    protected function getResourceForCollection(Collection $models)
    {
        return $this->encoder->getResourceForModel($models->first());
    }

    /**
     * Overidden to prevent redundant lookups.
     *
     * {@inheritdoc}
     */
    protected function getResourceForModel(Model $model)
    {
        if (null === $this->resource) {
            return parent::getResourceForModel($model);
        }

        return $this->resource;
    }


}
