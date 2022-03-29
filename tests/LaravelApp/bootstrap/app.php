<?php

use Allyson\MultiEnv\Tests\Orchestra\Commander;

$APP_KEY = $_SERVER['APP_KEY'] ?? $_ENV['APP_KEY'] ?? '';
$DB_CONNECTION = $_SERVER['DB_CONNECTION'] ?? $_ENV['DB_CONNECTION'] ??  'testing';

$config = ['env' => ['APP_KEY="'.$APP_KEY.'"', 'DB_CONNECTION="'.$DB_CONNECTION.'"'], 'providers' => []];

$app = (new Commander($config, getcwd()))->laravel();

unset($APP_KEY, $DB_CONNECTION, $config);

// $router = $app->make('router');

// collect(glob(__DIR__.'/../routes/{web,api}.php', GLOB_BRACE))
//     ->each(function ($routeFile) use ($app, $router) {
//         require $routeFile;
//     });

return $app;
