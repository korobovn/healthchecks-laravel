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
    ],

    'groups' => [
        'group-name' => [
            'Some\\Check\\Class',
            'One\\More\\Check\\Class',
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
    'route'   => '/status',

];
