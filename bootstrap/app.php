<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Cors;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\JwtRoleAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->append(CorsMiddleware::class);

        $middleware->alias([
            // 'cors' => CorsMiddleware::class,
            'auth' => Authenticate::class,
            'permission' => PermissionMiddleware::class,
            'role'       => RoleMiddleware::class,
            'jwt.role-admin' => JwtRoleAdminMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
