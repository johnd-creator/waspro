<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    /**
     * Build a standardized paginated API response.
     *
     * @param  class-string<\Illuminate\Http\Resources\Json\JsonResource>  $resourceClass
     */
    protected function respondWithPaginatedCollection(Request $request, LengthAwarePaginator $paginator, string $resourceClass): JsonResponse
    {
        $items = $resourceClass::collection($paginator->getCollection())->toArray($request);

        return ApiResponse::success([
            'items' => $items,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ]);
    }

    /**
     * Resolve the per-page size from the request while applying sane limits.
     */
    protected function perPage(Request $request, int $default = 15, int $max = 100): int
    {
        $perPage = (int) $request->input('per_page', $default);

        return max(1, min($perPage, $max));
    }

    /**
     * Resolve a nullable boolean filter from the request.
     */
    protected function booleanFromRequest(Request $request, string $key): ?bool
    {
        if (! $request->filled($key)) {
            return null;
        }

        $value = $request->input($key);

        return match (true) {
            in_array($value, [true, 1, '1', 'true'], true) => true,
            in_array($value, [false, 0, '0', 'false'], true) => false,
            default => null,
        };
    }
}
