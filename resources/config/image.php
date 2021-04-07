<?php

return [
    'storage' => [
        'disk' => 'public',

        'defaults' => [
            'encoding' => 'jpg',
            'quality'  => 70
        ],

        'presets' => [
            'source'  => [
                'folder' => 'images/source',
            ],
            '100x75'  => [
                'folder' => 'images/100x75',
                'fit'    => [100, 75],
            ],
            '320x240' => [
                'folder' => 'images/320x240',
                'fit'    => [320, 240],
            ],
            '640x480' => [
                'folder' => 'images/640x480',
                'fit'    => [640, 480],
            ],
            '900x600' => [
                'folder' => 'images/900x600',
                'fit'    => [900, 600],
            ]
        ]
    ],

    'placeholders' => [
        '100x75'  => 'assets/front/img/image-placeholder_100x75.png',
        '320x240' => 'assets/front/img/image-placeholder_320x240.png',
        '640x480' => 'assets/front/img/image-placeholder_640x480.png',
        '900x600' => 'assets/front/img/image-placeholder_900x600.png',
    ]
];
