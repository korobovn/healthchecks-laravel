<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Checks
    |--------------------------------------------------------------------------
    |
    | Feel free to add your own check classes to this array. Optionally you can
    | define check options as an array, for example:
    |
    | ```
    | Some\Check::class => ['option' => 'value'],
    | ```
    |
    */
    'checks' => [
        AvtoDev\HealthChecks\Checks\DatabaseAccessCheck::class,
        AvtoDev\HealthChecks\Checks\MigrationsCheck::class,
        AvtoDev\HealthChecks\Checks\RedisAccessCheck::class,
        AvtoDev\HealthChecks\Checks\ServersPingCheck::class => [
            'servers' => [
                '8.8.8.8',
                [
                    'host'    => 'www.google.com',
                    'port'    => '80',
                    'timeout' => '2', //timeout in seconds
                ],
            ],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | HTTP Route
    |--------------------------------------------------------------------------
    |
    | Set 'null' for disabling this feature.
    |
    */
    'route'  => '/status',

];
