<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ Trusted proxies (reverse proxy / load balancer: Nginx, Cloudflare, AWS ELB...)
        $trustedProxies = env('TRUSTED_PROXIES', '*');

        $middleware->trustProxies(
            at: $trustedProxies === '*' ? '*' : array_map('trim', explode(',', $trustedProxies)),
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_AWS_ELB,
        );

        // ✅ Para API: si no está autenticado, NO redirigir a route('login')
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*')) {
                return null; // => 401 JSON
            }

            // Si algún día tenés web login real, recién ahí te sirve esto:
            // return route('login');

            return null;
        });

        // ✅ Aliases de Spatie Permission (para usar en rutas)
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Si tenés middlewares propios, los alias acá:
        // $middleware->alias(['authorize' => \App\Http\Middleware\Authorize::class]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // ✅ 401 unauthenticated en API (JSON)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                // return response()->json(['message' => 'Unauthenticated.'], 401);
                return sendResponse(null, 'No autenticado', 401);
            }
            return null; // deja que el framework maneje web
        });

        // ✅ Spatie Permission: 403 cuando falta rol/permiso
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                // return response()->json(['message' => 'Forbidden.'], 403);
                return sendResponse(null, 'No tiene permisos suficientes', 403);
            }
            return null;
        });

        // ✅ 404
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Not Found.'], 404);
            }
            return null;
        });

        // ✅ 405
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Method Not Allowed.'], 405);
            }
            return null;
        });

        // ✅ 403 (AccessDenied)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            return null;
        });

        // ✅ DB errors: devolvés 500 controlado
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Database error.',
                    // En producción no conviene devolver $e->getMessage()
                ], 500);
            }
            return null;
        });
    })->create();
