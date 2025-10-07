<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\KategoriKegiatanSumberResource;
use App\Models\KategoriKegiatanSumber;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriKegiatanSumberController extends ApiController
{
    /**
     * Display a paginated listing of kategori kegiatan sumber.
     */
    public function index(Request $request): JsonResponse
    {
        $query = KategoriKegiatanSumber::query();

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $query->orderBy('nama_kategori');

        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, KategoriKegiatanSumberResource::class);
    }

    /**
     * Display the specified kategori kegiatan sumber.
     */
    public function show(Request $request, int $kategori): JsonResponse
    {
        $kategori = KategoriKegiatanSumber::findOrFail($kategori);

        return ApiResponse::success(
            (new KategoriKegiatanSumberResource($kategori))->toArray($request)
        );
    }
}
