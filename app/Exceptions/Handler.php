<?php

namespace App\Exceptions;

use App\Helpers\K3Logger;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
            // Log system errors using K3Logger
            if ($this->shouldReport($e)) {
                K3Logger::systemError($e, [
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'user_id' => Auth::id(),
                    'ip_address' => request()->ip(),
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle validation exceptions
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($request, $e);
        }

        // Handle HTTP exceptions
        if ($e instanceof HttpException) {
            return $this->handleHttpException($request, $e);
        }

        // Handle database exceptions
        if ($this->isDatabaseException($e)) {
            return $this->handleDatabaseException($request, $e);
        }

        // Handle authentication exceptions
        if ($this->isAuthenticationException($e)) {
            return $this->handleAuthenticationException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle validation exceptions with better error messages
     */
    protected function handleValidationException(Request $request, ValidationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $e->errors(),
                'status' => 'validation_error',
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Terdapat kesalahan dalam data yang dimasukkan.');
    }

    /**
     * Handle HTTP exceptions
     */
    protected function handleHttpException(Request $request, HttpException $e)
    {
        $statusCode = $e->getStatusCode();

        // Log security events for certain status codes
        if (in_array($statusCode, [401, 403, 404])) {
            K3Logger::securityEvent('HTTP_ERROR', [
                'status_code' => $statusCode,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getHttpErrorMessage($statusCode),
                'status' => 'http_error',
                'code' => $statusCode,
            ], $statusCode);
        }

        // Check if custom error view exists
        $view = "errors.{$statusCode}";
        if (view()->exists($view)) {
            return response()->view($view, ['exception' => $e], $statusCode);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle database exceptions
     */
    protected function handleDatabaseException(Request $request, Throwable $e)
    {
        K3Logger::systemError($e, [
            'type' => 'database_error',
            'query' => $this->extractQueryFromException($e),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.',
                'status' => 'database_error',
            ], 500);
        }

        return response()->view('errors.database', [
            'message' => 'Terjadi kesalahan pada database. Silakan coba lagi nanti.',
        ], 500);
    }

    /**
     * Handle authentication exceptions
     */
    protected function handleAuthenticationException(Request $request, Throwable $e)
    {
        K3Logger::securityEvent('AUTHENTICATION_FAILED', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anda harus login untuk mengakses halaman ini.',
                'status' => 'authentication_required',
            ], 401);
        }

        return redirect()->route('login')
            ->with('error', 'Anda harus login untuk mengakses halaman tersebut.');
    }

    /**
     * Check if exception is database related
     */
    protected function isDatabaseException(Throwable $e): bool
    {
        return $e instanceof \Illuminate\Database\QueryException ||
               $e instanceof \PDOException ||
               str_contains($e->getMessage(), 'database') ||
               str_contains($e->getMessage(), 'SQL');
    }

    /**
     * Check if exception is authentication related
     */
    protected function isAuthenticationException(Throwable $e): bool
    {
        return $e instanceof \Illuminate\Auth\AuthenticationException;
    }

    /**
     * Get user-friendly HTTP error messages
     */
    protected function getHttpErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Permintaan tidak valid.',
            401 => 'Anda harus login untuk mengakses halaman ini.',
            403 => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
            404 => 'Halaman yang Anda cari tidak ditemukan.',
            405 => 'Metode tidak diizinkan.',
            419 => 'Sesi Anda telah berakhir. Silakan refresh halaman.',
            429 => 'Terlalu banyak permintaan. Silakan coba lagi nanti.',
            500 => 'Terjadi kesalahan internal server.',
            502 => 'Server tidak dapat dijangkau.',
            503 => 'Layanan sedang dalam pemeliharaan.',
            default => 'Terjadi kesalahan yang tidak diketahui.'
        };
    }

    /**
     * Extract SQL query from database exception
     */
    protected function extractQueryFromException(Throwable $e): ?string
    {
        if ($e instanceof \Illuminate\Database\QueryException) {
            return $e->getSql();
        }

        // Try to extract query from message
        if (preg_match('/SQL: (.+?)(?:\s+\(|$)/', $e->getMessage(), $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Determine if the exception should be reported
     */
    public function shouldReport(Throwable $e): bool
    {
        // Don't report certain exceptions
        $dontReport = [
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Illuminate\Validation\ValidationException::class,
        ];

        foreach ($dontReport as $type) {
            if ($e instanceof $type) {
                return false;
            }
        }

        return parent::shouldReport($e);
    }
}
