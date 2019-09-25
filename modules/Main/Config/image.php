<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',
    'enable_webp' => true,
    'thumbs' => [
        'extralarge' => 2000,
        'large' => 1200,
        'medium' => 600,
        'small' => 400,
        'extrasmall' => 200,
        'thumb' => 100
    ],
    'crop' => 500,
    'quality' => 60,

];
