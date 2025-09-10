<?php

return [
    'branding' => [
        "app_name"    => "koneko.mx",
        "title"       => "Koneko Soluciones Tecnológicas",
        "description" => "Koneko Soluciones Tecnológicas ofrece desarrollo de sistemas empresariales, sitios web profesionales, inteligencia artificial, infraestructura y soluciones digitales avanzadas para negocios en México.",
        "author"      => "arturo@koneko.mx",
        "namespace"   => env('KONEKO_NAMESPACE', 'koneko'),
        "app_logo"        => "vendor/koneko-vuexy-admin/img/logo/koneko-04.png",
        "app_logo_h"      => "vendor/koneko-vuexy-admin/img/logo/horizontal-circulo-03.png",
        "app_logo_dark"   => "vendor/koneko-vuexy-admin/img/logo/koneko-04.png",
        "app_logo_h_dark" => "vendor/koneko-vuexy-admin/img/logo/horizontal-circulo-01.png",
        "favicon"         => "vendor/koneko-vuexy-admin/img/logo/koneko-04.png",
    ],
    // ================== 📦 CACHE GENERAL ==================
    'cache' => [
        'enabled' => (bool) env('KONEKO_CACHE_ENABLED', true),
        'ttl'     => (int) env('KONEKO_CACHE_TTL', 20 * 24 * 60),  // 20 días
    ],

    // ================== 📦 CACHE DE COMPONENTE ==================
    'core' => [
        'cache' => [
            'enabled' => (bool) env('KONEKO_CORE_CACHE_ENABLED', true),
            'ttl'     => (int) env('KONEKO_CORE_CACHE_TTL', 20 * 24 * 60),
        ],
    ],
];
