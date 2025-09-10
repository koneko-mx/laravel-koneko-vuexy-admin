<?php

return [
    'channels' => [
        'vuexy' => [
            'driver' => 'daily',
            'path' => storage_path('logs/koneko-vuexy-admin.log'),
            'level' => env('VUEXY_LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],
];