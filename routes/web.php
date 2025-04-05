<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/env-check', function () {
    return env('APP_NAME', 'Not loaded');
});

