<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LogPenyimpananResource;
use App\Models\ApprovalLog;
use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogPenyimpananController extends ApiController
{
    /**
     * Delta listing: return logs updated since a timestamp.
     */
    public function index(Request $request): JsonResponse
    {
        $query = LogPenyimpananLimbah::query()->with(['jenisLimbah']);

        if ($since = $request->query('updated_since')) {
            try {
                $sinceTs = Carbon::parse($since);
                $query->where('updated_at', '>=', $sinceTs);
            } catch (\Throwable $e) {
                return ApiResponse::error('Parameter updated_since tidak valid (ISO8601).', 422);
            }
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', (int) $request->input('unit_id'));
        }

        if ($request->filled('status_log')) {
            $query->where('status_log', $request->input('status_log'));
        }

        $query->orderBy('updated_at', 'asc');
        $paginator = $query->paginate($this->perPage($request))->appends($request->query());

        return $this->respondWithPaginatedCollection($request, $paginator, LogPenyimpananResource::class);
    }

    /**
     * Bulk upsert logs from offline queue.
     */
    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'max:100'],
            'items.*.client_uuid' => ['required', 'string'],
            'items.*.updated_at_client' => ['required', 'date'],
            'items.*.created_at_client' => ['nullable', 'date'],
            // minimal domain fields; others optional
            'items.*.kode_limbah' => ['required', 'string'],
            'items.*.tanggal_limbah_masuk' => ['required', 'date'],
            'items.*.jumlah_limbah_masuk' => ['required', 'numeric'],
            'items.*.detail_sumber_limbah' => ['nullable', 'string'],
            'items.*.perusahaan_id' => ['nullable', 'integer'],
            'items.*.unit_id' => ['nullable', 'integer'],
            'items.*.status_log' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        if (! $user) {
            return ApiResponse::unauthorized();
        }

        $results = [];

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $clientUuid = (string) $item['client_uuid'];
                $now = Carbon::now();

                // Normalize
                $unitId = $item['unit_id'] ?? $user->unit_id;
                $statusLog = $item['status_log'] ?? 'Tersimpan';
                $createdClient = isset($item['created_at_client']) ? Carbon::parse($item['created_at_client']) : null;
                $updatedClient = Carbon::parse($item['updated_at_client']);

                $existing = LogPenyimpananLimbah::where('client_uuid', $clientUuid)->first();

                // Compute maksimal_penyimpanan_tanggal and tanggal_kadaluarsa from jenis limbah if possible
                $tanggalMasuk = Carbon::parse($item['tanggal_limbah_masuk']);
                $jenis = JenisLimbah::find($item['kode_limbah']);
                $days = (int) ($jenis?->waktu_penyimpanan_hari ?? 0);
                $kadaluarsa = $days > 0 ? $tanggalMasuk->copy()->addDays($days) : null;

                $payload = [
                    'kode_identitas' => $item['kode_identitas'] ?? null,
                    'tanggal_limbah_masuk' => $tanggalMasuk,
                    'detail_sumber_limbah' => $item['detail_sumber_limbah'] ?? '',
                    'jumlah_limbah_masuk' => $item['jumlah_limbah_masuk'],
                    'maksimal_penyimpanan_tanggal' => $kadaluarsa,
                    'status_log' => $statusLog,
                    'tanggal_pengangkutan' => isset($item['tanggal_pengangkutan']) ? Carbon::parse($item['tanggal_pengangkutan']) : null,
                    'jumlah_diangkut' => $item['jumlah_diangkut'] ?? 0,
                    'user_id' => $user->user_id,
                    'kode_limbah' => $item['kode_limbah'],
                    'perusahaan_id' => $item['perusahaan_id'] ?? null,
                    'unit_id' => $unitId,
                    'tanggal_kadaluarsa' => $kadaluarsa,
                    'client_uuid' => $clientUuid,
                    'created_at_client' => $createdClient,
                    'updated_at_client' => $updatedClient,
                    'synced_at' => $now,
                ];

                try {
                    if ($existing) {
                        // Conflict check using updated_at_client
                        if ($existing->updated_at_client && $existing->updated_at_client >= $updatedClient) {
                            $results[$clientUuid] = [
                                'id' => $existing->log_id,
                                'status' => 'skipped',
                                'message' => 'Older or equal client update; skipped.',
                            ];

                            continue;
                        }

                        $existing->fill($payload);
                        $existing->save();

                        $results[$clientUuid] = [
                            'id' => $existing->log_id,
                            'status' => 'updated',
                            'message' => 'Updated successfully.',
                        ];
                    } else {
                        // Create new
                        // Ensure UUID format if client sends random string
                        if (! Str::isUuid($clientUuid)) {
                            // keep as-is but server still allows unique string
                        }

                        $new = LogPenyimpananLimbah::create($payload);

                        $results[$clientUuid] = [
                            'id' => $new->log_id,
                            'status' => 'created',
                            'message' => 'Created successfully.',
                        ];
                    }
                } catch (\Throwable $e) {
                    $results[$clientUuid] = [
                        'id' => $existing->log_id ?? null,
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return ApiResponse::error('Gagal melakukan sinkronisasi.', 500, [], null, config('app.debug') ? ['exception' => $e->getMessage()] : []);
        }

        return ApiResponse::success(['results' => $results], 'Sinkronisasi berhasil.');
    }

    /**
     * Store a newly created log.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_limbah' => ['required', 'exists:jenis_limbah,kode_limbah'],
            'tanggal_limbah_masuk' => ['required', 'date'],
            'jumlah_limbah_masuk' => ['required', 'numeric', 'min:0'],
            'detail_sumber_limbah' => ['nullable', 'string'],
            'perusahaan_id' => ['nullable', 'integer', 'exists:perusahaan_penghasil,perusahaan_id'],
            'unit_id' => ['nullable', 'integer', 'exists:unit_pembangkit,unit_id'],
            'status_log' => ['nullable', 'string'],
            'tanggal_pengangkutan' => ['nullable', 'date'],
            'jumlah_diangkut' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = $request->user();
        if (! $user) {
            return ApiResponse::unauthorized();
        }

        $tanggalMasuk = Carbon::parse($validated['tanggal_limbah_masuk']);
        $jenis = JenisLimbah::find($validated['kode_limbah']);
        $days = (int) ($jenis?->waktu_penyimpanan_hari ?? 0);
        $kadaluarsa = $days > 0 ? $tanggalMasuk->copy()->addDays($days) : null;

        $log = LogPenyimpananLimbah::create([
            'kode_identitas' => $request->input('kode_identitas'),
            'tanggal_limbah_masuk' => $tanggalMasuk,
            'detail_sumber_limbah' => $validated['detail_sumber_limbah'] ?? '',
            'jumlah_limbah_masuk' => $validated['jumlah_limbah_masuk'],
            'maksimal_penyimpanan_tanggal' => $kadaluarsa,
            'status_log' => $validated['status_log'] ?? 'Tersimpan',
            'tanggal_pengangkutan' => isset($validated['tanggal_pengangkutan']) ? Carbon::parse($validated['tanggal_pengangkutan']) : null,
            'jumlah_diangkut' => $validated['jumlah_diangkut'] ?? 0,
            'user_id' => $user->user_id,
            'kode_limbah' => $validated['kode_limbah'],
            'perusahaan_id' => $validated['perusahaan_id'] ?? null,
            'unit_id' => $validated['unit_id'] ?? $user->unit_id,
            'tanggal_kadaluarsa' => $kadaluarsa,
        ]);

        return (new LogPenyimpananResource($log))->response()->setStatusCode(201);
    }

    /**
     * Display the specified log.
     */
    public function show($id): JsonResponse
    {
        $log = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])->findOrFail($id);

        return ApiResponse::success((new LogPenyimpananResource($log))->toArray(request()));
    }

    /**
     * Update the specified log.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $log = LogPenyimpananLimbah::findOrFail($id);

        $user = $request->user();
        if (! $user) {
            return ApiResponse::unauthorized();
        }
        // Non-admin cannot modify logs from other unit
        if (! $user->isAdmin() && $log->unit_id !== $user->unit_id) {
            return ApiResponse::error('Tidak diizinkan memperbarui log dari unit lain.', 403);
        }

        $validated = $request->validate([
            'kode_limbah' => ['sometimes', 'exists:jenis_limbah,kode_limbah'],
            'tanggal_limbah_masuk' => ['sometimes', 'date'],
            'jumlah_limbah_masuk' => ['sometimes', 'numeric', 'min:0'],
            'detail_sumber_limbah' => ['sometimes', 'nullable', 'string'],
            'perusahaan_id' => ['sometimes', 'nullable', 'integer', 'exists:perusahaan_penghasil,perusahaan_id'],
            'unit_id' => ['sometimes', 'nullable', 'integer', 'exists:unit_pembangkit,unit_id'],
            'status_log' => ['sometimes', 'string'],
            'tanggal_pengangkutan' => ['sometimes', 'nullable', 'date'],
            'jumlah_diangkut' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ]);

        // Recompute expiry when relevant fields change
        $kodeLimbah = $validated['kode_limbah'] ?? $log->kode_limbah;
        $tanggalMasuk = isset($validated['tanggal_limbah_masuk']) ? Carbon::parse($validated['tanggal_limbah_masuk']) : $log->tanggal_limbah_masuk;
        $jenis = JenisLimbah::find($kodeLimbah);
        $days = (int) ($jenis?->waktu_penyimpanan_hari ?? 0);
        $kadaluarsa = $days > 0 ? Carbon::parse($tanggalMasuk)->copy()->addDays($days) : null;

        $log->fill(array_merge($validated, [
            'tanggal_limbah_masuk' => $tanggalMasuk,
            'kode_limbah' => $kodeLimbah,
            'tanggal_kadaluarsa' => $kadaluarsa,
            'maksimal_penyimpanan_tanggal' => $kadaluarsa,
        ]));
        $log->save();

        return ApiResponse::success((new LogPenyimpananResource($log))->toArray($request), 'Log diperbarui.');
    }

    /**
     * Remove the specified log from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $log = LogPenyimpananLimbah::findOrFail($id);

        $user = $request->user();
        if (! $user) {
            return ApiResponse::unauthorized();
        }
        if (! $user->isAdmin() && $log->unit_id !== $user->unit_id) {
            return ApiResponse::error('Tidak diizinkan menghapus log dari unit lain.', 403);
        }

        $log->delete();

        return response()->json(null, 204);
    }

    public function approve(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->canApproveLogs()) {
            return ApiResponse::error('Anda tidak memiliki akses untuk menyetujui log limbah.', 403);
        }

        $log = LogPenyimpananLimbah::findOrFail($id);

        if ($log->approval_status === 'approved') {
            return ApiResponse::error('Log limbah sudah disetujui.');
        }

        $log->update([
            'approval_status' => 'approved',
            'approved_by' => $user->user_id,
            'approved_at' => now(),
        ]);

        ApprovalLog::create([
            'log_id' => $log->log_id,
            'approved_by' => $user->user_id,
            'action' => 'approve',
            'rejected_reason' => null,
        ]);

        return ApiResponse::success((new LogPenyimpananResource($log))->toArray(request()), 'Log limbah berhasil disetujui.');
    }

    public function reject(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->canApproveLogs()) {
            return ApiResponse::error('Anda tidak memiliki akses untuk menolak log limbah.', 403);
        }

        $log = LogPenyimpananLimbah::findOrFail($id);

        if ($log->approval_status === 'rejected') {
            return ApiResponse::error('Log limbah sudah ditolak.');
        }

        $validated = $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $log->update([
            'approval_status' => 'rejected',
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        ApprovalLog::create([
            'log_id' => $log->log_id,
            'approved_by' => $user->user_id,
            'action' => 'reject',
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        return ApiResponse::success((new LogPenyimpananResource($log))->toArray(request()), 'Log limbah berhasil ditolak.');
    }
}
