<?php

use Pixelindustries\JsonApi\Contracts\Support\Error\ErrorDataInterface;
use Pixelindustries\JsonApi\Encoder\Transformers\ErrorDataTransformer;

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

        // If this is enabled, default includes defined in resources will only be applied
        // at the top level. Any nested resources that are included will NOT have their
        // default includes processed unless specifically requested.
        'top-level-default-includes-only' => true,

        // If this is enabled, the encoder will automatically attempt to determine the
        // URL to be used for links relative to the top level resource.
        'auto-determine-top-resource-url' => true,

        'links' => [
            // The segment to add for relationship links:
            // as in
            //      <base URL>/<resource>/<relationships>/<include key>
            //      http://api.somewhere.com/post/relationships/comments
            'relationships-segment' => 'relationships',
        ],

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
            ErrorDataInterface::class => ErrorDataTransformer::class,
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
