<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__.'/../vendor/autoload.php';

if (file_exists(__DIR__.'/../.env.testing')) {
    $dotenv = Dotenv::createImmutable(__DIR__.'/..', '.env.testing');
    $dotenv->safeLoad();
}

$token = getenv('TEST_TOKEN');

if (is_string($token) && $token !== '') {
    $cache = sys_get_temp_dir().'/shopper-testbench-'.$token;
    $_SERVER['APP_SERVICES_CACHE'] = $_ENV['APP_SERVICES_CACHE'] = $cache.'-services.php';
    $_SERVER['APP_PACKAGES_CACHE'] = $_ENV['APP_PACKAGES_CACHE'] = $cache.'-packages.php';
}
