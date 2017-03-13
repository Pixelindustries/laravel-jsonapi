<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Transform responses
    |--------------------------------------------------------------------------
    |
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

];
