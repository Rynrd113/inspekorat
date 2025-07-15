<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle API requests
        if ($request->is('api/*') || $request->wantsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Validation errors
        if ($e instanceof ValidationException) {
            return ApiResponse::validationError(
                $e->errors(),
                'Validation failed'
            );
        }

        // Model not found
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::notFound(
                'Resource not found'
            );
        }

        // 404 Not Found
        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::notFound(
                'Endpoint not found'
            );
        }

        // 405 Method Not Allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error(
                'Method not allowed',
                405
            );
        }

        // Authentication errors
        if ($e instanceof AuthenticationException) {
            return ApiResponse::unauthorized(
                'Authentication required'
            );
        }

        // Authorization errors
        if ($e instanceof AuthorizationException) {
            return ApiResponse::forbidden(
                'Access denied'
            );
        }

        // HTTP exceptions
        if ($e instanceof HttpException) {
            return ApiResponse::error(
                $e->getMessage() ?: 'An error occurred',
                $e->getStatusCode()
            );
        }

        // General exceptions
        if (config('app.debug')) {
            return ApiResponse::error(
                $e->getMessage(),
                500,
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }

        return ApiResponse::serverError(
            'An unexpected error occurred'
        );
    }

    /**
     * Handle unauthenticated users
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return ApiResponse::unauthorized('Authentication required');
        }

        return redirect()->guest(route('login'));
    }
}
