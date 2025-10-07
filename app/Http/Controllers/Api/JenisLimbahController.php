<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\JenisLimbahResource;
use App\Models\JenisLimbah;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JenisLimbahController extends ApiController
{
    /**
     * Display a paginated listing of jenis limbah.
     */
    public function index(Request $request): JsonResponse
    {
        $query = JenisLimbah::query()->with('karakteristik');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($qb) use ($search): void {
                $qb->where('nama_limbah', 'like', "%{$search}%")
                    ->orWhere('kode_limbah', 'like', "%{$search}%")
                    ->orWhere('deskripsi_limbah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('karakteristik_id')) {
            $query->where('karakteristik_id', $request->input('karakteristik_id'));
        }

        if (($status = $this->booleanFromRequest($request, 'status_aktif')) !== null) {
            $query->where('status_aktif', $status);
        }

        $query->orderBy('nama_limbah');

        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, JenisLimbahResource::class);
    }

    /**
     * Display the specified jenis limbah.
     */
    public function show(Request $request, string $kode_limbah): JsonResponse
    {
        $jenisLimbah = JenisLimbah::with('karakteristik')->findOrFail($kode_limbah);

        return ApiResponse::success(
            (new JenisLimbahResource($jenisLimbah))->toArray($request)
        );
    }
}
