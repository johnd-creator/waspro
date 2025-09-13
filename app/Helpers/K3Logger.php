<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class K3Logger
{
    /**
     * Log audit activities
     */
    public static function audit(string $action, array $data = [], ?string $model = null, ?int $modelId = null): void
    {
        $user = Auth::user();

        $logData = [
            'action' => $action,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'model' => $model,
            'model_id' => $modelId,
            'data' => $data,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_audit')->info('K3 Audit Log', $logData);
    }

    /**
     * Log user login activity
     */
    public static function loginActivity(string $email, bool $success, ?string $reason = null): void
    {
        $logData = [
            'email' => $email,
            'success' => $success,
            'reason' => $reason,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        $channel = $success ? 'k3_audit' : 'k3_error';
        $level = $success ? 'info' : 'warning';

        Log::channel($channel)->$level('Login Activity', $logData);
    }

    /**
     * Log data access activity
     */
    public static function dataAccess(string $resource, string $action, array $filters = []): void
    {
        $user = Auth::user();

        $logData = [
            'resource' => $resource,
            'action' => $action,
            'filters' => $filters,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'ip_address' => request()?->ip(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_audit')->info('Data Access Log', $logData);
    }

    /**
     * Log system errors with context
     */
    public static function systemError(\Throwable $exception, array $context = []): void
    {
        $user = Auth::user();

        $logData = [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'url' => request()?->fullUrl(),
            'method' => request()?->method(),
            'ip_address' => request()?->ip(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_error')->error('System Error', $logData);
    }

    /**
     * Log database operations
     */
    public static function databaseOperation(string $operation, string $table, array $data = [], ?int $recordId = null): void
    {
        $user = Auth::user();

        $logData = [
            'operation' => $operation, // CREATE, UPDATE, DELETE
            'table' => $table,
            'record_id' => $recordId,
            'data' => $data,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_audit')->info('Database Operation', $logData);
    }

    /**
     * Log file operations
     */
    public static function fileOperation(string $operation, string $filename, ?string $path = null): void
    {
        $user = Auth::user();

        $logData = [
            'operation' => $operation, // UPLOAD, DOWNLOAD, DELETE
            'filename' => $filename,
            'path' => $path,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'ip_address' => request()?->ip(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_audit')->info('File Operation', $logData);
    }

    /**
     * Log security events
     */
    public static function securityEvent(string $event, array $details = []): void
    {
        $user = Auth::user();

        $logData = [
            'event' => $event,
            'details' => $details,
            'user_id' => $user?->id,
            'user_name' => $user?->nama_lengkap ?? 'System',
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('k3_error')->warning('Security Event', $logData);
    }

    /**
     * Log performance metrics
     */
    public static function performance(string $operation, float $executionTime, array $metrics = []): void
    {
        $logData = [
            'operation' => $operation,
            'execution_time' => $executionTime,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'metrics' => $metrics,
            'timestamp' => now()->toISOString(),
        ];

        // Only log if execution time is above threshold
        $threshold = config('app.log_slow_query_threshold', 2000); // milliseconds
        if ($executionTime > $threshold) {
            Log::channel('k3_audit')->warning('Slow Operation', $logData);
        }
    }
}
