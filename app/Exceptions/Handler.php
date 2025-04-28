<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Register custom error page handlers
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return response()->view('errors.403', [], 403);
        });

        // Handle RouteNotFoundException
        $this->renderable(function (RouteNotFoundException $e) {
            return response()->view('errors.route-not-found', [
                'message' => $e->getMessage(),
                'exception' => get_class($e)
            ], 404);
        });

        // Handle generic server errors
        $this->renderable(function (Throwable $e) {
            if ($this->shouldReport($e) && !$this->isHttpException($e)) {
                return response()->view('errors.500', [
                    'message' => $e->getMessage(),
                    'exception' => get_class($e)
                ], 500);
            }
        });
    }
}