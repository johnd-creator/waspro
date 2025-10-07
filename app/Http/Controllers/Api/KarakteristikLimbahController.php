<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\KarakteristikLimbahResource;
use App\Models\KarakteristikLimbah;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KarakteristikLimbahController extends ApiController
{
    /**
     * Display a paginated listing of karakteristik limbah.
     */
    public function index(Request $request): JsonResponse
    {
        $query = KarakteristikLimbah::query();

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where('nama_karakteristik', 'like', "%{$search}%");
        }

        if (($status = $this->booleanFromRequest($request, 'status_aktif')) !== null) {
            $query->where('status_aktif', $status);
        }

        $query->orderBy('nama_karakteristik');

        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, KarakteristikLimbahResource::class);
    }

    /**
     * Display the specified karakteristik limbah.
     */
    public function show(Request $request, int $karakteristik): JsonResponse
    {
        $karakteristik = KarakteristikLimbah::findOrFail($karakteristik);

        return ApiResponse::success(
            (new KarakteristikLimbahResource($karakteristik))->toArray($request)
        );
    }
}
