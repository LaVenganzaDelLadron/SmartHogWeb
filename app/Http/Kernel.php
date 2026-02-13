<?php

namespace App\Http;
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        // you can add global middleware here if needed
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        'auth.custom' => \App\Http\Middleware\AuthMiddleware::class,
    ];
}
