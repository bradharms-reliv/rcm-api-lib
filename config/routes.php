<?php
/**
 * routes.php
 */
return [
    [
        'name' => 'modules.rcm-api-lib.dist.rcm-api-lib.js',
        'path' => '/modules/rcm-api-lib/dist/rcm-api-lib.js',
        'middleware' => Reliv\RcmApiLib\Middleware\RcmApiLibJsController::class,
        'options' => [],
        'allowed_methods' => ['GET'],
    ],
    [
        'name' => 'modules.rcm-api-lib.dist.rcm-api-lib.min.js',
        'path' => '/modules/rcm-api-lib/dist/rcm-api-lib.min.js',
        'middleware' => Reliv\RcmApiLib\Middleware\RcmApiLibJsController::class,
        'options' => [],
        'allowed_methods' => ['GET'],
    ],
    [
        'name' => 'modules.rcm-api-lib.dist.rcm-api-lib.min.js.map',
        'path' => '/modules/rcm-api-lib/dist/rcm-api-lib.min.js.map',
        'middleware' => Reliv\RcmApiLib\Middleware\RcmApiLibMinJsMapController::class,
        'options' => [],
        'allowed_methods' => ['GET'],
    ],
];