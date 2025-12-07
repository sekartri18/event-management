<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Alias Middleware (Kode lama Anda)
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        // =================================================================
        // ✅ 2. MATIKAN CSRF UNTUK MIDTRANS (WAJIB DITAMBAHKAN)
        // =================================================================
        // Tanpa ini, Laravel akan menolak notifikasi Midtrans (Error 419)
        // karena Midtrans tidak mengirim token keamanan CSRF.
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification', // Izinkan route ini diakses tanpa token
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();