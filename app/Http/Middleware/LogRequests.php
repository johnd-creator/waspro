<?php

namespace App\Http\Middleware;

use App\Helpers\K3Logger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Skip logging for certain routes
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        // Log incoming request
        $this->logIncomingRequest($request);

        $response = $next($request);

        // Calculate execution time
        $executionTime = (microtime(true) - $startTime) * 1000; // milliseconds

        // Log outgoing response
        $this->logOutgoingResponse($request, $response, $executionTime);

        return $response;
    }

    /**
     * Determine if logging should be skipped for this request
     */
    private function shouldSkipLogging(Request $request): bool
    {
        $skipRoutes = [
            'telescope*',
            'horizon*',
            '_debugbar*',
            'livewire*',
            '*.css',
            '*.js',
            '*.map',
            '*.ico',
            '*.png',
            '*.jpg',
            '*.jpeg',
            '*.gif',
            '*.svg',
            '*.woff',
            '*.woff2',
            '*.ttf',
            '*.eot',
        ];

        foreach ($skipRoutes as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log incoming request
     */
    private function logIncomingRequest(Request $request): void
    {
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route()?->getName(),
            'parameters' => $this->sanitizeData($request->all()),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ];

        Log::channel('k3_audit')->info('Incoming Request', $logData);
    }

    /**
     * Log outgoing response
     */
    private function logOutgoingResponse(Request $request, Response $response, float $executionTime): void
    {
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route()?->getName(),
            'status_code' => $response->getStatusCode(),
            'execution_time' => round($executionTime, 2),
            'memory_usage' => memory_get_usage(true),
            'user_id' => Auth::id(),
        ];

        // Log as error if status code indicates an error
        if ($response->getStatusCode() >= 400) {
            Log::channel('k3_error')->error('Error Response', $logData);
        } else {
            Log::channel('k3_audit')->info('Outgoing Response', $logData);
        }

        // Log performance if slow
        K3Logger::performance(
            $request->route()?->getName() ?? $request->path(),
            $executionTime,
            ['status_code' => $response->getStatusCode()]
        );
    }

    /**
     * Sanitize sensitive data from request parameters
     */
    private function sanitizeData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'ssn',
            'social_security',
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    /**
     * Sanitize sensitive headers
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'x-api-key',
            'x-auth-token',
            'cookie',
        ];

        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['[REDACTED]'];
            }
        }

        return $headers;
    }
}
