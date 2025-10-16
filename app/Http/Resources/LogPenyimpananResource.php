<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogPenyimpananResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'log_id' => $this->log_id,
            'client_uuid' => $this->client_uuid,
            'kode_identitas' => $this->kode_identitas,
            'tanggal_limbah_masuk' => optional($this->tanggal_limbah_masuk)->toIso8601String(),
            'detail_sumber_limbah' => $this->detail_sumber_limbah,
            'jumlah_limbah_masuk' => (float) $this->jumlah_limbah_masuk,
            'maksimal_penyimpanan_tanggal' => optional($this->maksimal_penyimpanan_tanggal)->toIso8601String(),
            'status_log' => $this->status_log,
            'tanggal_pengangkutan' => optional($this->tanggal_pengangkutan)->toIso8601String(),
            'jumlah_diangkut' => (float) $this->jumlah_diangkut,
            'kode_limbah' => $this->kode_limbah,
            'perusahaan_id' => $this->perusahaan_id,
            'unit_id' => $this->unit_id,
            'tanggal_kadaluarsa' => optional($this->tanggal_kadaluarsa)->toIso8601String(),
            'expiry_status' => $this->expiry_status,
            // client timestamps
            'created_at_client' => optional($this->created_at_client)->toIso8601String(),
            'updated_at_client' => optional($this->updated_at_client)->toIso8601String(),
            'synced_at' => optional($this->synced_at)->toIso8601String(),
            // server timestamps
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
