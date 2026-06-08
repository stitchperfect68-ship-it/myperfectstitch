<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'supabase.auth'      => \App\Http\Middleware\VerifySupabaseAuth::class,
            'customer.auth'      => \App\Http\Middleware\RequireCustomerAuth::class,
        ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            return $request->is('admin*') ? route('login') : route('auth.login');
        });

        // sb_token is set by JavaScript so it must not go through Laravel's encryption
        $middleware->encryptCookies(except: ['sb_token']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
