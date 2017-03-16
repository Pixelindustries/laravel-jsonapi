<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use Illuminate\Pagination\AbstractPaginator;
use UnexpectedValueException;

class PaginatedModelsTransformer extends ModelCollectionTransformer
{

    /**
     * Transforms given data.
     *
     * @param AbstractPaginator $models
     * @return array
     */
    public function transform($models)
    {
        if ( ! ($models instanceof AbstractPaginator)) {
            throw new UnexpectedValueException("ModelTransformer expects AbstractPaginator instance");
        }

        $data = parent::transform($models->getCollection());

        // Inject pagination

        return $data;
    }

}
