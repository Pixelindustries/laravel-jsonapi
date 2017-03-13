<?php
namespace Pixelindustries\JsonApi\Fractal\Transformers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SimpleModelTransformer
 *
 * Very basic transformer that does not support includes and
 * performs a simple toArray on the record.
 */
class SimpleModelTransformer extends AbstractModelTransformer
{

    /**
     * Performs the transformation for a model record.
     *
     * @param Model $record
     * @return mixed
     */
    protected function doTransform(Model $record)
    {
        return $record->toArray();
    }

}
