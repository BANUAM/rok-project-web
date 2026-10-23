<?php

// Fix for Vercel Serverless routing:
// In Vercel, requests dispatched through api/index.php set SCRIPT_NAME to /api/index.php.
// Symfony Request detects /api as the base URL and strips it from the route pathinfo.
// Setting SCRIPT_NAME and PHP_SELF to /index.php ensures Symfony preserves the full request URI.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Ensure writable temporary directories and SQLite database on Vercel Serverless
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('VERCEL')) {
    $tmpDirs = [
        '/tmp/storage/app/public',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/testing',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];

    foreach ($tmpDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    $dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? ($_SERVER['DB_CONNECTION'] ?? 'sqlite'));
    if ($dbConnection === 'sqlite') {
        $tmpDb = '/tmp/database.sqlite';
        $bundledDb = __DIR__ . '/../database/database.sqlite';
        if (!file_exists($tmpDb) || filesize($tmpDb) === 0) {
            if (file_exists($bundledDb) && filesize($bundledDb) > 0) {
                @copy($bundledDb, $tmpDb);
            } else {
                @touch($tmpDb);
            }
        }
        if (file_exists($tmpDb)) {
            @chmod($tmpDb, 0666);
        }
    }
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
