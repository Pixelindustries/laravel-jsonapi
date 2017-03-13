<?php
namespace Pixelindustries\JsonApi\Fractal\Transformers;

use League\Fractal\TransformerAbstract;

abstract class AbstractJsonApiTransformer extends TransformerAbstract
{

    /**
     * Explicitly defined JSON-API type.
     *
     * If this is not set, the type will be automatically determined by the TypeMaker.
     *
     * @var string
     */
    protected $type;

    /**
     * Transforms a model record.
     *
     * This also determines and splits off the JSON-API type.
     *
     * @param mixed $content
     * @return array
     */
    public function transform($content)
    {
        return [ 'type' => $this->type ]
             + $this->forceStringValues((array) $content);
    }

    /**
     * Forces scalar values in an array to string format.
     *
     * @param array $data
     * @return array
     */
    protected function forceStringValues(array $data)
    {
        array_walk($data, function (&$value) {
            if ( ! is_scalar($value)) return;
            $value = (string) $value;
        });

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function item($data, $transformer, $resourceKey = null)
    {
        if (null === $data) {
            return $this->null();
        }

        return parent::item($data, $transformer, $resourceKey);
    }

}
