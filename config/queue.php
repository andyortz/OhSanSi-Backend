<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    */
    'default' => env('QUEUE_CONNECTION', 'sync'),  // Cambiado de 'database' a 'sync'

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    */
    'batching' => [
        'database' => env('DB_CONNECTION', 'pgsql'),  // Usa tu conexión principal
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    */
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'pgsql'),  // Usa tu conexión principal
        'table' => 'failed_jobs',
    ],
];