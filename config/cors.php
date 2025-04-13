<?php

return [
    'paths' => ['api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register' 
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://laravel-app-mjrd.onrender.com'], // Replace with your app's domain in production
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];