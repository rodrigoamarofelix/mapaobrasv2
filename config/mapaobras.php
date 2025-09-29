<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SSL Certificate Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para certificados SSL do sistema
    |
    */

    'certificates' => [
        'enabled' => env('SSL_ENABLED', false),
        'cert_path' => env('SSL_CERT_PATH', storage_path('app/certificates/localhost.pem')),
        'key_path' => env('SSL_KEY_PATH', storage_path('app/certificates/localhost-key.pem')),
        'verify_peer' => env('SSL_VERIFY_PEER', false),
        'verify_peer_name' => env('SSL_VERIFY_PEER_NAME', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações específicas do banco de dados do sistema de obras
    |
    */

    'database' => [
        'obras_table' => 'mapaobras',
        'projeto_obras_table' => 'projeto_obras',
        'municipios_table' => 'municipios',
        'markers_table' => 'markers',
        'publicacao_table' => 'publicacao',
        'edicao_table' => 'edicao',
        'tipo_table' => 'tipo',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com APIs externas
    |
    */

    'api' => [
        'timeout' => env('API_TIMEOUT', 30),
        'retry_attempts' => env('API_RETRY_ATTEMPTS', 3),
        'cache_ttl' => env('API_CACHE_TTL', 3600), // 1 hora
    ],

    /*
    |--------------------------------------------------------------------------
    | Map Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para mapas e geolocalização
    |
    */

    'map' => [
        'default_zoom' => env('MAP_DEFAULT_ZOOM', 10),
        'default_lat' => env('MAP_DEFAULT_LAT', -15.7801),
        'default_lng' => env('MAP_DEFAULT_LNG', -47.9292),
        'cluster_enabled' => env('MAP_CLUSTER_ENABLED', true),
    ],
];

