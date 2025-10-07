<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UnitPembangkitResource;
use App\Models\UnitPembangkit;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitPembangkitController extends ApiController
{
    /**
     * Display a paginated listing of unit pembangkit.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', UnitPembangkit::class);

        $query = UnitPembangkit::query();

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($qb) use ($search): void {
                $qb->where('nama_unit', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('telepon_unit', 'like', "%{$search}%");
            });
        }

        if ($kota = $request->input('kota')) {
            $query->where('kota', $kota);
        }

        if (($status = $this->booleanFromRequest($request, 'status_aktif')) !== null) {
            $query->where('status_aktif', $status);
        }

        $query->orderBy('nama_unit');

        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, UnitPembangkitResource::class);
    }

    /**
     * Store a newly created unit pembangkit.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', UnitPembangkit::class);

        $data = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
            'alamat_unit' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'telepon_unit' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $unit = UnitPembangkit::create($data);

        return ApiResponse::success(
            (new UnitPembangkitResource($unit))->toArray($request),
            'Unit pembangkit berhasil dibuat.',
            201
        );
    }

    /**
     * Display the specified unit pembangkit.
     */
    public function show(Request $request, UnitPembangkit $unit_pembangkit): JsonResponse
    {
        $this->authorize('view', $unit_pembangkit);

        return ApiResponse::success(
            (new UnitPembangkitResource($unit_pembangkit))->toArray($request)
        );
    }

    /**
     * Update the specified unit pembangkit.
     */
    public function update(Request $request, UnitPembangkit $unit_pembangkit): JsonResponse
    {
        $this->authorize('update', $unit_pembangkit);

        $data = $request->validate([
            'nama_unit' => ['sometimes', 'string', 'max:255'],
            'alamat_unit' => ['sometimes', 'nullable', 'string'],
            'kota' => ['sometimes', 'nullable', 'string', 'max:100'],
            'kode_pos' => ['sometimes', 'nullable', 'string', 'max:10'],
            'telepon_unit' => ['sometimes', 'nullable', 'string', 'max:20'],
            'keterangan' => ['sometimes', 'nullable', 'string'],
            'status_aktif' => ['sometimes', 'boolean'],
        ]);

        $unit_pembangkit->fill($data)->save();

        return ApiResponse::success(
            (new UnitPembangkitResource($unit_pembangkit))->toArray($request),
            'Unit pembangkit berhasil diperbarui.'
        );
    }

    /**
     * Remove the specified unit pembangkit.
     */
    public function destroy(UnitPembangkit $unit_pembangkit): JsonResponse
    {
        $this->authorize('delete', $unit_pembangkit);

        $unit_pembangkit->delete();

        return ApiResponse::success(null, 'Unit pembangkit berhasil dihapus.');
    }
}
