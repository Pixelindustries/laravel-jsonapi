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

        'type' => [
            // The namespace for records (models) to left-trim for creating type:
            // If 'App\Models', then App\Models\Pages\Page gets dasherized as type: pages--page.
            // If null, only the classname of the model will be used.
            // If empty string (''), the full namespace will be used.
            'trim-namespace' => null,
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

];
