<?php

$env = YII_ENV;
$log = array();
$logVars = ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER', '!_POST.password'];
$maskVars = ['_SERVER.DB_CONNECTION, _SERVER.DB_HOST, _SERVER.DB_PORT, _SERVER.DB_NAME, _SERVER.DB_USERNAME, _SERVER.DB_PASSWORD'];
$devEmail = ['susanto2025.id@gmail.com', 'susanto7768@gmail.com'];

if ('dev' != $env)
{
    $log = [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [

            ]
        ],
    ];
}

return [
    'log' => $log
];
