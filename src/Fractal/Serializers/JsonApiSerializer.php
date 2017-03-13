<?php
namespace Pixelindustries\JsonApi\Fractal\Serializers;

use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer as FractalJsonApiSerializer;

class JsonApiSerializer extends FractalJsonApiSerializer
{

    /**
     * {@inheritdoc}
     */
    public function __construct($baseUrl = null)
    {
        $baseUrl = $baseUrl ?: '/api';
        parent::__construct($baseUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function item($resourceKey, array $data)
    {
        $type = array_pull($data, 'type');

        return parent::item($type ?: $resourceKey, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function includedData(ResourceInterface $resource, array $data)
    {

        // todo
        // based on the resource contents, determine whether we
        // want included data at all (or just stick to the relationship/ids
        // keep in mind that resource may be item or collection...

        return parent::includedData($resource, $data);
    }

}
