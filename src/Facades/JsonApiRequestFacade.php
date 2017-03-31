<?php
namespace Pixelindustries\JsonApi\Facades;

use Illuminate\Support\Facades\Facade;
use Pixelindustries\JsonApi\Contracts\Support\Request\RequestQueryParserInterface;

/**
 * Class JsonApiRequestFacade
 *
 * @see RequestQueryParserInterface
 * @see \Pixelindustries\JsonApi\Support\Request\RequestQueryParser
 */
class JsonApiRequestFacade extends Facade
{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return RequestQueryParserInterface::class;
    }

}
