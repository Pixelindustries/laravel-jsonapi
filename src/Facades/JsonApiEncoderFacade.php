<?php
namespace Pixelindustries\JsonApi\Facades;

use Illuminate\Support\Facades\Facade;
use Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface;

/**
 * Class JsonApiEncoderFacade
 *
 * @see \Pixelindustries\JsonApi\Encoder\Encoder
 * @see \Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface
 */
class JsonApiEncoderFacade extends Facade
{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return EncoderInterface::class;
    }

}
