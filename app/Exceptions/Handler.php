<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            $code = 500;

            if ($exception instanceof ValidationException) {
                $code = 422;

                return response()->json([
                    'success' => false,
                    'errors' => $exception->errors(),
                ], $code);
            } elseif ($exception instanceof AuthenticationException) {
                $code = 401;
            } elseif ($exception instanceof ModelNotFoundException) {
                $code = 404;
            }

            Log::error('Exception occurred: ' . $exception->getMessage(), ['exception' => $exception]);

            $json = [
                'success' => false,
                'error' => [
                    'code' => $exception->getCode() ?: $code,
                    'message' => $exception->getMessage(),
                ],
            ];

            return response()->json($json, $code);
        }

        return parent::render($request, $exception);
    }
}
