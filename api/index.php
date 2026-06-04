<?php

// Vercel serverless environment is strictly read-only except for /tmp.
// Laravel requires a writable storage directory for compiled views, caches, and logs.
// We must dynamically redirect Laravel's storage path to /tmp/storage during boot.

$tmpStorage = '/tmp/storage';

if (!file_exists($tmpStorage)) {
    mkdir($tmpStorage, 0777, true);
    mkdir($tmpStorage . '/app', 0777, true);
    mkdir($tmpStorage . '/framework', 0777, true);
    mkdir($tmpStorage . '/framework/cache', 0777, true);
    mkdir($tmpStorage . '/framework/sessions', 0777, true);
    mkdir($tmpStorage . '/framework/views', 0777, true);
    mkdir($tmpStorage . '/logs', 0777, true);
}

// Bind the storage path to the temporary directory
putenv('APP_STORAGE=' . $tmpStorage);
$_ENV['APP_STORAGE'] = $tmpStorage;
$_SERVER['APP_STORAGE'] = $tmpStorage;

// Redirect caches
$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/routes.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/storage/events.php';
putenv('APP_SERVICES_CACHE=/tmp/storage/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/storage/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/storage/config.php');
putenv('APP_ROUTES_CACHE=/tmp/storage/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/storage/events.php');



require __DIR__ . '/../public/index.php';
