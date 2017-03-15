<?php
namespace Pixelindustries\JsonApi\Encoder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerFactoryInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerInterface;
use Pixelindustries\JsonApi\Contracts\Repositories\ResourceRepositoryInterface;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;

class Encoder implements EncoderInterface
{
    const DATA_KEY     = 'data';
    const LINKS_KEY    = 'links';
    const INCLUDED_KEY = 'included';


    /**
     * Sideloaded included data.
     *
     * @var Collection
     */
    protected $included;

    /**
     * Top level links.
     *
     * @var Collection
     */
    protected $links;

    /**
     * The includes that were marked as requested by the client.
     *
     * @var string[]
     */
    protected $requestedIncludes = [];

    /**
     * @var TransformerFactoryInterface
     */
    protected $transformerFactory;

    /**
     * @var ResourceRepositoryInterface
     */
    protected $resourceRepository;


    /**
     * @param TransformerFactoryInterface $transformerFactory
     * @param ResourceRepositoryInterface $resourceRepository
     */
    public function __construct(
        TransformerFactoryInterface $transformerFactory,
        ResourceRepositoryInterface $resourceRepository
    ) {
        $this->transformerFactory = $transformerFactory;
        $this->resourceRepository = $resourceRepository;

        $this->included = new Collection;
        $this->links    = new Collection;
    }


    /**
     * Encodes given data as JSON-API encoded data array.
     *
     * @param mixed $data
     * @return array
     */
    public function encode($data)
    {
        $encoded = [];

        // First, perform the transformation, which should also update the encoder
        // with included data, links, meta data, etc.
        $encoded[ static::DATA_KEY ] = $this->transform($data);


        // Serialize collected data and decorate the encoded data with it.
        if ($this->hasLinks()) {
            $encoded[ static::LINKS_KEY ] = $this->serializeLinks();
        }


        // Make sure top resource is not in the included data
        $id   = array_get($encoded[ static::DATA_KEY ], 'id');
        $type = array_get($encoded[ static::DATA_KEY ], 'type');

        if (null !== $type && null !== $id) {
            $this->removeFromIncludedDataByTypeAndId($type, $id);
        }

        if ($this->hasIncludedData()) {
            $encoded[ static::INCLUDED_KEY ] = $this->serializeIncludedData();
        }

        return $encoded;
    }

    /**
     * Returns transformer for given data in this context.
     *
     * @param mixed $data
     * @param bool  $topLevel
     * @return TransformerInterface
     */
    public function makeTransformer($data, $topLevel = false)
    {
        $transformer = $this->transformerFactory->makeFor($data);

        $transformer->setEncoder($this);

        if ($topLevel) {
            $transformer->setIsTop();
        }

        return $transformer;
    }

    /**
     * Transforms data for top level data key.
     *
     * Transformers may recursively prepare further nested data,
     * and may add included data on this encoder to be side-loaded.
     *
     * @param mixed $data
     * @return mixed
     */
    protected function transform($data)
    {
        $transformer = $this->makeTransformer($data, true);

        return $transformer->transform($data);
    }

    // ------------------------------------------------------------------------------
    //      Links and meta
    // ------------------------------------------------------------------------------

    /**
     * Returns the base URI to use for the API.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return rtrim(config('jsonapi.base_url'), '/');
    }

    /**
     * Sets a top-level link.
     *
     * @param string       $key
     * @param string|array $link    may be string or [ href, meta ] array
     * @return $this
     */
    public function setLink($key, $link)
    {
        $this->links->put($key, $link);

        return $this;
    }

    /**
     * Removes a top level link.
     *
     * @param string $key
     * @return $this
     */
    public function removeLink($key)
    {
        $this->links->forget($key);

        return $this;
    }


    // ------------------------------------------------------------------------------
    //      Includes, requested
    // ------------------------------------------------------------------------------

    /**
     * Sets requested includes for transformation.
     *
     * @param array $includes
     * @return $this
     */
    public function setRequestedIncludes(array $includes)
    {
        $this->requestedIncludes = $includes;

        return $this;
    }

    /**
     * Returns currently registered requested includes.
     *
     * @return string[]
     */
    public function getRequestedIncludes()
    {
        return $this->requestedIncludes;
    }

    /**
     * Returns whether any links were collected.
     *
     * @return bool
     */
    protected function hasLinks()
    {
        return $this->links->isNotEmpty();
    }

    /**
     * Returns collected top level links as array.
     *
     * @return array
     */
    protected function serializeLinks()
    {
        return $this->links->toArray();
    }


    // ------------------------------------------------------------------------------
    //      Included data, side-loading
    // ------------------------------------------------------------------------------

    /**
     * Adds data to be included by side-loading.
     *
     * @param mixed       $data
     * @param string|null $identifier    uniquely identifies the included data, if possible
     * @return $this
     */
    public function addIncludedData($data, $identifier = null)
    {
        if (null === $identifier) {
            $this->included->push($data);
        } elseif ( ! $this->included->has($identifier)) {
            $this->included->put($identifier, $data);
        }

        return $this;
    }

    /**
     * Removes included data by identifier.
     *
     * @param string $identifier
     * @return $this
     */
    public function removeIncludedData($identifier)
    {
        $this->included->forget($identifier);

        return $this;
    }

    /**
     * Removes included data by a given type and id.
     *
     * @param string $type
     * @param string $id
     * @return $this
     */
    public function removeFromIncludedDataByTypeAndId($type, $id)
    {
        return $this->removeIncludedData($type . ':' . $id);
    }

    /**
     * Returns whether any data to be included was collected.
     *
     * @return bool
     */
    protected function hasIncludedData()
    {
        return $this->included->isNotEmpty();
    }

    /**
     * Returns collected included data as array.
     *
     * @return array
     */
    protected function serializeIncludedData()
    {
        return $this->included->values()->toArray();
    }


    // ------------------------------------------------------------------------------
    //      Resource provision
    // ------------------------------------------------------------------------------

    /**
     * Returns resource for given model instance.
     *
     * @param Model $model
     * @return null|ResourceInterface
     */
    public function getResourceForModel(Model $model)
    {
        $resource = $this->resourceRepository->getByModel($model);

        if ($resource) {
            $resource->setModel($model);
        }

        return $resource;
    }

    /**
     * Returns resource for given JSON-API resource type.
     *
     * @param string $type
     * @return null|ResourceInterface
     */
    public function getResourceForType($type)
    {
        return $this->resourceRepository->getByType($type);
    }

}
