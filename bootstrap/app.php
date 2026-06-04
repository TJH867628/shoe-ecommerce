<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'payment/toyyibpay/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = 'The selected images are too large to upload together. Please upload fewer images at a time or reduce each image size.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            if ($request->hasSession()) {
                return back()->withErrors(['images' => $message]);
            }

            return response()->view('errors.upload-too-large', [
                'message' => $message,
                'backUrl' => $request->headers->get('referer', url('/')),
            ], 413);
        });
    })->create();
