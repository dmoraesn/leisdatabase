<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection
    |--------------------------------------------------------------------------
    */
    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [

        /*
        |--------------------------------------------------------------------------
        | SQLite (opcional / testes)
        |--------------------------------------------------------------------------
        */
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        /*
        |--------------------------------------------------------------------------
        | MySQL / MariaDB — BANCO LOCAL (leisdatabase)
        |--------------------------------------------------------------------------
        */
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),

            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),

            'database' => env('DB_DATABASE', 'leisdatabase'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),

            'unix_socket' => env('DB_SOCKET', ''),

            // ⚠️ UTF-8 jurídico obrigatório
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),

            'prefix' => '',
            'prefix_indexes' => true,

            'strict' => true,
            'engine' => null,

'options' => extension_loaded('pdo_mysql')
    ? array_filter([
        \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ])
    : [],

        ],

        /*
        |--------------------------------------------------------------------------
        | MySQL — BANCO EXTERNO (LEGISLLA)
        |--------------------------------------------------------------------------
        */
        'legislla' => [
            'driver' => 'mysql',

            'host' => env('DB_LEGISLLA_HOST', '127.0.0.1'),
            'port' => env('DB_LEGISLLA_PORT', '3306'),

            'database' => env('DB_LEGISLLA_DATABASE', 'legislla'),
            'username' => env('DB_LEGISLLA_USERNAME', 'root'),
            'password' => env('DB_LEGISLLA_PASSWORD', ''),

            'unix_socket' => env('DB_LEGISLLA_SOCKET', ''),

            // Mesmo charset para evitar bugs de integração
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',

            'prefix' => '',
            'prefix_indexes' => true,

            'strict' => true,
            'engine' => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | PostgreSQL (não usado atualmente)
        |--------------------------------------------------------------------------
        */
        'pgsql' => [
            'driver' => 'pgsql',

            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),

            'database' => env('DB_DATABASE', 'leisdatabase'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', ''),

            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,

            'search_path' => 'public',
            'sslmode' => env('DB_SSLMODE', 'prefer'),
        ],

        /*
        |--------------------------------------------------------------------------
        | SQL Server (não usado atualmente)
        |--------------------------------------------------------------------------
        */
        'sqlsrv' => [
            'driver' => 'sqlsrv',

            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),

            'database' => env('DB_DATABASE', 'leisdatabase'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', ''),

            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository
    |--------------------------------------------------------------------------
    */
    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Configuration
    |--------------------------------------------------------------------------
    */
    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => Str::slug(
                env('APP_NAME', 'leisdatabase'),
                '_'
            ) . '_database_',
        ],

        'default' => [
            'url' => env('REDIS_URL'),

            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),

            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),

            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),

            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];
