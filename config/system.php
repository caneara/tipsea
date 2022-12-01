<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Banner Limits
    |--------------------------------------------------------------------------
    |
    | This value controls the maximum number of banners that a user may add to
    | their account profile (and by extension, use within their code tips).
    |
    */

    'banner_limit' => 5,

    /*
    |--------------------------------------------------------------------------
    | Forbidden Content
    |--------------------------------------------------------------------------
    |
    | This section includes the content that is forbidden from being used by
    | anyone e.g. email addresses, domain names, user names, names etc.
    |
    */

    'forbidden' => [
        'domains' => [
            //
        ],
        'names' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Dimensions
    |--------------------------------------------------------------------------
    |
    | This section controls the width and height in pixels that should be used
    | for images that are associated with the given resource.
    |
    */

    'image_dimensions' => [
        'Banner' => ['action' => 'resize', 'width' => 2000, 'height' => 250],
        'Tip'    => ['action' => 'fit',    'width' => 1600, 'height' => 800],
        'User'   => ['action' => 'fit',    'width' => 300,  'height' => 300],
    ],

];
