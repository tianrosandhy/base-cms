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
        'extralarge' => [
            'type' => 'keep-ratio',
            'width' => 1500,
            'height' => 1500
        ],
        'large' => [
            'type' => 'keep-ratio',
            'width' => 1200,
            'height' => 1200
        ],
        'medium' => [
            'type' => 'keep-ratio',
            'width' => 700,
            'height' => 700
        ],
        'small' => [
            'type' => 'keep-ratio',
            'width' => 400,
            'height' => 400
        ],
        'cropped' => [
            'type' => 'fit',
            'width' => 400,
            'height' => 400
        ],
        'thumb' => [
            'type' => 'fit',
            'width' => 100,
            'height' => 100
        ],
    ],
    'origin_maximum_width' => 2000, //set to null to disable
    'quality' => 80,

];
