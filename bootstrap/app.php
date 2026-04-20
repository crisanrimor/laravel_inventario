<?php

use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role'                => RoleMiddleware::class,
            'permission'          => PermissionMiddleware::class,
            'role_or_permission'  => RoleOrPermissionMiddleware::class,
            'check.status'        => CheckUserStatus::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $th) {
            $request = request(); // helper global de Laravel

            Log::error('Error inesperado', [
                'mensaje'    => $th->getMessage(),
                'archivo'    => $th->getFile(),
                'linea'      => $th->getLine(),
                'url'        => $request->fullUrl(),
                'ruta'       => $request->route()?->getName(),
                'metodo'     => $request->method(),
                'usuario_id' => auth()->id()
            ]);

            return false;
        });

        $exceptions->render(function (Throwable $th, Request $request) {
            // Renderizar páginas para 403, 404.
            if ($th instanceof HttpException) {
                return Inertia::render('Errors/ErrorPage', [
                    'status' => $th->getStatusCode(),
                ])->toResponse($request)->setStatusCode($th->getStatusCode());
            }

            if (!$th instanceof ValidationException && !$th instanceof HttpException && !$th instanceof AuthenticationException) {
                if ($request->expectsJson()) { // Es para rutas de api que esperan respuestas en JSON.
                    return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
                }
                return redirect()->back()->with('error', 'Ocurrió un error inesperado.')
                    ?? redirect()->route('dashboard.home')->with('error', 'Ocurrió un error inesperado.');
            }
        });
    })->create();
