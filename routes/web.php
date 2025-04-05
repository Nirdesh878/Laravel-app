<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/env-check', function () {
    return env('APP_NAME', 'Not loaded');
});

// routes/web.php
Route::get('/health', function () {
    return [
        'app_name' => config('app.name'),
        'env' => config('app.env'),
        'db' => DB::connection()->getDatabaseName(),
    ];
});

Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!File::exists($logFile)) return 'Log file not found.';
    return nl2br(e(File::get($logFile)));
});

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return '✅ Connected to database: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return '❌ DB connection failed: ' . $e->getMessage();
    }
});

Route::get('/db-debug', function () {
    return config('database');
});

Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
        return 'Connected to database successfully: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Database connection error: ' . $e->getMessage();
    }
});

Route::get('/debug', function () {
    return phpinfo();
});





