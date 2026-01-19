<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Orchid Platform
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Orchid'),

    'domain' => env('ORCHID_DOMAIN', null),

    'prefix' => env('ORCHID_PREFIX', '/admin'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'auth' => [
        'guard' => 'web',
        'password' => 'password',
    ],

    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    |
    | ⚠️ CONFIGURAÇÃO CRÍTICA
    | O disco DEVE ser o mesmo do FILESYSTEM_DISK
    |
    */

    'attachment' => [
        'disk' => env('ORCHID_ATTACHMENT_DISK', 'public'),
        'generator' => Orchid\Attachment\Engines\Generator::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    */

    'icons' => [
        'path' => 'orchid/icons',
    ],

];
