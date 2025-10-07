<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ApiResponse
{
    /**
     * Build a successful API response.
     */
    public static function success(mixed $data = null, string $message = 'OK', int $status = 200, array $meta = []): JsonResponse
    {
        return response()->json(
            self::structure('success', $message, $data, null, $meta),
            $status
        );
    }

    /**
     * Build an error API response.
     */
    public static function error(string $message, int $status = 400, array $errors = [], mixed $data = null, array $meta = []): JsonResponse
    {
        return response()->json(
            self::structure('error', $message, $data, $errors ?: null, $meta),
            $status
        );
    }

    /**
     * Build a validation error response.
     */
    public static function validationError(array $errors, string $message = 'Data yang diberikan tidak valid.'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Build an unauthorized response.
     */
    public static function unauthorized(string $message = 'Anda harus login untuk mengakses resource ini.'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Normalize the response payload structure.
     */
    protected static function structure(string $status, string $message, mixed $data, ?array $errors, array $meta): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
            'meta' => $meta ?: null,
            'timestamp' => Carbon::now()->toIso8601String(),
        ];
    }
}
