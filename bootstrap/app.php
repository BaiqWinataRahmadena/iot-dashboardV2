<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         // Pastikan alias middleware Anda tidak hilang
        $middleware->alias([
            'dummy.auth' => \App\Http\Middleware\CheckDummyLogin::class,
        ]);
        
        // Matikan CSRF khusus untuk API (Agar PowerShell tidak ditolak)
        $middleware->validateCsrfTokens(except: [
            'api/*', // Semua yang berawalan api/ bebas CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
