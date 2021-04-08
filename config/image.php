<?php

return [


    'model' => Kovalovme\Image\Models\Image::class,

    'disk' => 'public',

    'default_preset' => [
        'folder' => 'general',
        'encoding' => 'jpg',
        'quality'  => 70
    ],

    'presets' => [
        'source'  => [
            'folder' => 'source',
        ],
        '100x75'  => [
            'folder' => '100x75',
            'fit'    => [100, 75],
        ],
        '320x240' => [
            'folder' => '320x240',
            'fit'    => [320, 240],
        ],
        '640x480' => [
            'folder' => '640x480',
            'fit'    => [640, 480],
        ],
        '900x600' => [
            'folder' => '900x600',
            'fit'    => [900, 600],
        ]
    ],
];
