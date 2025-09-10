<?php
return [
    'default_disk' => env('KONEKO_MEDIA_DISK', 'public'),
    'paths' => [
        'logo'    => 'media/brand',
        'favicon' => 'media/favicon',
        'share'   => 'media/{module}/{scope}/{owner}/share',
    ],
    'share' => [
        'default_area'    => 756000,
        'default_aspect'  => 1.91,
        'default_fit'     => 'cover',
        'default_format'  => 'auto',
        'default_quality' => 80,
        'default_bg'      => '#ffffff',
    ],
    'favicon_sizes' => [
        '16x16'   => [1=>16,0=>16],
        '76x76'   => [1=>76,0=>76],
        '120x120' => [1=>120,0=>120],
        '152x152' => [1=>152,0=>152],
        '180x180' => [1=>180,0=>180],
        '192x192' => [1=>192,0=>192],
    ],
];
