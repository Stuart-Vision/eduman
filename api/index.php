<?php

/*
 * Vercel serverless entry.
 *
 * The deployment filesystem is read-only except /tmp, so Laravel's storage
 * directory and the demo SQLite database are placed there on cold start.
 * Changes made in the demo reset whenever a new function instance starts.
 */

$storage = '/tmp/storage';
foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
    if (! is_dir("$storage/$dir")) {
        mkdir("$storage/$dir", 0755, true);
    }
}
$_ENV['LARAVEL_STORAGE_PATH'] = $_SERVER['LARAVEL_STORAGE_PATH'] = $storage;

$database = '/tmp/database.sqlite';
if (! file_exists($database)) {
    copy(__DIR__.'/../database/demo.sqlite', $database);
}

require __DIR__.'/../public/index.php';
