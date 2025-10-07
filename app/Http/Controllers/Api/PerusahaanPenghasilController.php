<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PerusahaanPenghasilResource;
use App\Models\PerusahaanPenghasil;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerusahaanPenghasilController extends ApiController
{
    /**
     * Display a paginated listing of perusahaan penghasil.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', PerusahaanPenghasil::class);

        $query = PerusahaanPenghasil::query();

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($qb) use ($search): void {
                $qb->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('person_in_charge', 'like', "%{$search}%");
            });
        }

        if ($kota = $request->input('kota')) {
            $query->where('kota', $kota);
        }

        if ($jenis = $request->input('jenis_perusahaan')) {
            $query->where('jenis_perusahaan', $jenis);
        }

        if (($status = $this->booleanFromRequest($request, 'status_aktif')) !== null) {
            $query->where('status_aktif', $status);
        }

        $query->orderBy('nama_perusahaan');

        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, PerusahaanPenghasilResource::class);
    }

    /**
     * Store a newly created perusahaan penghasil.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', PerusahaanPenghasil::class);

        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255', 'unique:perusahaan_penghasil,nama_perusahaan'],
            'jenis_perusahaan' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:20'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'alamat_perusahaan' => ['required', 'string'],
            'person_in_charge' => ['nullable', 'string', 'max:100'],
            'status_aktif' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $perusahaan = PerusahaanPenghasil::create($data);

        return ApiResponse::success(
            (new PerusahaanPenghasilResource($perusahaan))->toArray($request),
            'Perusahaan penghasil berhasil dibuat.',
            201
        );
    }

    /**
     * Display the specified perusahaan penghasil.
     */
    public function show(Request $request, PerusahaanPenghasil $perusahaan_penghasil): JsonResponse
    {
        $this->authorize('view', $perusahaan_penghasil);

        return ApiResponse::success(
            (new PerusahaanPenghasilResource($perusahaan_penghasil))->toArray($request)
        );
    }

    /**
     * Update the specified perusahaan penghasil.
     */
    public function update(Request $request, PerusahaanPenghasil $perusahaan_penghasil): JsonResponse
    {
        $this->authorize('update', $perusahaan_penghasil);

        $data = $request->validate([
            'nama_perusahaan' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('perusahaan_penghasil', 'nama_perusahaan')->ignore($perusahaan_penghasil->perusahaan_id, 'perusahaan_id'),
            ],
            'jenis_perusahaan' => ['sometimes', 'nullable', 'string', 'max:100'],
            'npwp' => ['sometimes', 'nullable', 'string', 'max:20'],
            'telepon' => ['sometimes', 'nullable', 'string', 'max:20'],
            'email' => ['sometimes', 'nullable', 'email', 'max:100'],
            'kota' => ['sometimes', 'nullable', 'string', 'max:100'],
            'alamat_perusahaan' => ['sometimes', 'required', 'string'],
            'person_in_charge' => ['sometimes', 'nullable', 'string', 'max:100'],
            'status_aktif' => ['sometimes', 'boolean'],
            'keterangan' => ['sometimes', 'nullable', 'string'],
        ]);

        $perusahaan_penghasil->fill($data)->save();

        return ApiResponse::success(
            (new PerusahaanPenghasilResource($perusahaan_penghasil))->toArray($request),
            'Perusahaan penghasil berhasil diperbarui.'
        );
    }

    /**
     * Remove the specified perusahaan penghasil.
     */
    public function destroy(PerusahaanPenghasil $perusahaan_penghasil): JsonResponse
    {
        $this->authorize('delete', $perusahaan_penghasil);

        $perusahaan_penghasil->delete();

        return ApiResponse::success(null, 'Perusahaan penghasil berhasil dihapus.');
    }
}
