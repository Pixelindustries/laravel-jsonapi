<?php
namespace Pixelindustries\JsonApi\Support\Transform;

use Czim\DataObject\AbstractDataObject;

/**
 * Class ResourceContainer
 *
 * Container which may hold a model, collection of models or other transformable data.
 *
 * @property mixed $data
 * @property bool  $reference       whether the data should be treated as type/id reference only,
 *                                  which means it should NOT get sideloaded into the include section.
 */
class ResourceContainer extends AbstractDataObject
{

    /**
     * @var array
     */
    protected $attributes = [
        'reference' => false,
        'include'   => true,
    ];

}
