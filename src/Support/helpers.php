<?php

if ( ! function_exists('jsonapi')) {
    /**
     * Returns JSON-API parser instance.
     *
     * @return \Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface
     */
    function jsonapi()
    {
        return app(\Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface::class);
    }
}
