<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'nida.verified' => \App\Http\Middleware\RequireNidaVerification::class,
            'company.verified' => \App\Http\Middleware\RequireCompanyVerification::class,
            'otp.required' => \App\Http\Middleware\RequireOtpVerification::class,
        ]);
        $middleware->web(prepend: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\RequireCompanyVerification::class,
            \App\Http\Middleware\RequireOtpVerification::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if (config('app.debug')) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $status === 500
                        ? 'Something went wrong. Please try again later.'
                        : 'Request could not be completed.',
                ], $status);
            }

            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }

            return response()->view('errors.500', [], 500);
        });
    })->create();
