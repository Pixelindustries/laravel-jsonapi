<?php
namespace Pixelindustries\JsonApi\Fractal\Transformers;

use Illuminate\Database\Eloquent\Model;
use Pixelindustries\JsonApi\Support\Type\TypeMaker;

abstract class AbstractModelTransformer extends AbstractJsonApiTransformer
{

    /**
     * Transforms a model record.
     *
     * This also determines and splits off the JSON-API type.
     *
     * @param Model $record
     * @return array
     */
    public function transform($record)
    {
        if ( ! ($record instanceof Model)) {
            throw new \InvalidArgumentException("AbstractModelTransformer only accepts Model instance");
        }

        $type = $this->type ?: $this->getJsonApiTypeForModel($record);

        return [ 'type' => $type ]
             + $this->forceStringValues($this->doTransform($record));
    }

    /**
     * Performs the transformation for a model record.
     *
     * @param Model $record
     * @return mixed
     */
    abstract protected function doTransform(Model $record);

    /**
     * Returns JSON-API type for a given record.
     *
     * This renders a dasherized version of the base name for the model class.
     *
     *
     * @param Model       $record
     * @param string|null $offsetNamespace      the part of the namespace to ignore; if null, only uses basename
     * @return string
     */
    protected function getJsonApiTypeForModel(Model $record, $offsetNamespace = null)
    {
        /** @var TypeMaker $maker */
        $maker = app(TypeMaker::class);

        return $maker->makeForModel($record, $offsetNamespace);
    }

}
