<?php

use Symfony\Component\HttpFoundation\AcceptHeader;

if ( ! function_exists('jsonapi')) {
    /**
     * Returns JSON-API parser instance.
     *
     * @return \Pixelindustries\JsonApi\Contracts\Support\Request\RequestQueryParserInterface
     */
    function jsonapi()
    {
        return app(\Pixelindustries\JsonApi\Contracts\Support\Request\RequestQueryParserInterface::class);
    }
}

if ( ! function_exists('jsonapi_response')) {
    /**
     * Casts a given array as a JSON-API response.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return \Pixelindustries\JsonApi\Http\Responses\JsonApiReponse
     */
    function jsonapi_response($data = null, $status = 200, $headers = [], $options = 0)
    {
        return new \Pixelindustries\JsonApi\Http\Responses\JsonApiReponse($data, $status, $headers, $options);
    }
}

if ( ! function_exists('jsonapi_encode')) {
    /**
     * Encodes a JSON-API array with a fresh encoder instance.
     *
     * @param  mixed $data
     * @param  array  $includes
     * @return array
     */
    function jsonapi_encode($data, array $includes = null)
    {
        /** @var \Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface $encoder */
        $encoder = app(\Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface::class);

        return $encoder->encode($data, $includes);
    }
}

if ( ! function_exists('jsonapi_error')) {
    /**
     * Makes a JSON-API response instance for an error or exception.
     *
     * @param  mixed $data
     * @return \Pixelindustries\JsonApi\Http\Responses\JsonApiReponse
     */
    function jsonapi_error($data)
    {
        $encoded = jsonapi_encode($data);

        $status = (int) array_get($encoded, 'error.status', 500);

        return jsonapi_response($encoded, $status);
    }
}

if ( ! function_exists('is_jsonapi_request')) {
    /**
     * Returns whether the current request is JSON-API.
     *
     * @return bool
     */
    function is_jsonapi_request()
    {
        $acceptHeader = AcceptHeader::fromString(request()->header('accept'));

        return $acceptHeader->has('application/vnd.api+json');
    }
}
