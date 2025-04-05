<?php

use Illuminate\Support\Facades\Route;

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

