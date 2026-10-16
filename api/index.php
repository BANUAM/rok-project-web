<?php

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
            @mkdir($dir, 0755, true);
        }
    }

    $dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? ($_SERVER['DB_CONNECTION'] ?? 'sqlite'));
    if ($dbConnection === 'sqlite') {
        $tmpDb = '/tmp/database.sqlite';
        $bundledDb = __DIR__ . '/../database/database.sqlite';
        if (!file_exists($tmpDb)) {
            if (file_exists($bundledDb)) {
                @copy($bundledDb, $tmpDb);
            } else {
                @touch($tmpDb);
            }
        }
    }
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
