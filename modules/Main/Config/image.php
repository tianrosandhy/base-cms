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
    'thumbs' => [
        'large' => 1200,
        'medium' => 600,
        'small' => 300,
        'thumb' => 100
    ],
    'crop' => 300,
    'quality' => 60,

];
