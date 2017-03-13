<?php
namespace Pixelindustries\JsonApi\Facades;

use Illuminate\Support\Facades\Facade;
use Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface;

/**
 * Class JsonApiRequestFacade
 *
 * @see RequestParserInterface
 * @see \Pixelindustries\JsonApi\Support\Request\RequestParser
 */
class JsonApiRequestFacade extends Facade
{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return RequestParserInterface::class;
    }

}
