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

require __DIR__ . '/../public/index.php';
