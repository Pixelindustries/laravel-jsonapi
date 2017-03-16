<?php

return [

    // The base relative API url to use for JSON-API links.
    'base_url' => '/api',

    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    */

    'repository' => [

        'resource' => [

            // Base namespace in which model-namespace-mirrored classes with resource
            // configurations should be placed.
            //
            // A model:
            //      App\\Models\\Pages\\Post
            // should have a resource in:
            //      App\\JsonApi\\Resources\\Pages\\Post.php
            //
            'namespace' => 'App\\JsonApi\\Resources\\',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Transform responses
    |--------------------------------------------------------------------------
    */

    'transform' => [

        // As per JSON-API spec, this will ignore default includes set for resources when
        // includes are requested by the client.
        'requested-includes-cancel-defaults' => true,

        // Generating JSON-API type from Eloquent models.
        'type' => [

            // The namespace for records (models) to left-trim for creating type:
            // If 'App\Models', then App\Models\Pages\Page gets dasherized as type: pages--page.
            // If null, only the classname of the model will be used.
            // If empty string (''), the full namespace will be used.
            'trim-namespace' => null,
        ],

        // Fallback mapping transformers to content type.
        // The TransformerFactory will use this to instantiate transformers based on an is_a() match
        // on given content, if no standard match was found.
        'map' => [
            // \Your\ClassHere::class => \Your\Transformer\ClassHere::class
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [

        // Default page size
        'size' => 25,
    ],


    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    |
    | The JSON-API query string request data is parsed using these options.
    |
    */

    'request' => [

        // Request values are read from the query string using the following keys
        'keys' => [
            'filter'  => 'filter',
            'include' => 'include',
            'page'    => 'page',
            'sort'    => 'sort',
        ],

        'include' => [
            // The token by which the include strings are separated, if multiple includes are given.
            'separator' => ',',
        ],

        'sort' => [
            // The token by which the sort strings are separated, if multiple sort attributes are given.
            'separator' => ',',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */

    'exceptions' => [

        // Mapping for status code to use for specific exception classes
        'status' => [
            \League\OAuth2\Server\Exception\OAuthException::class              => 403,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class        => 404,
            \Czim\Filter\Exceptions\FilterDataValidationFailedException::class => 422,
            \Illuminate\Validation\ValidationException::class                  => 422,
        ],
    ],

];
