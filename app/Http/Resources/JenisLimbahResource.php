<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JenisLimbahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'kode_limbah' => $this->kode_limbah,
            'nama_limbah' => $this->nama_limbah,
            'kemasan' => $this->kemasan,
            'jumlah_ton_per_tahun' => $this->jumlah_ton_per_tahun,
            'waktu_penyimpanan_hari' => $this->waktu_penyimpanan_hari,
            'batas_penyimpanan_hari' => $this->batas_penyimpanan_hari,
            'deskripsi_limbah' => $this->deskripsi_limbah,
            'status_aktif' => (bool) $this->status_aktif,
            'karakteristik' => $this->whenLoaded('karakteristik', fn () => [
                'karakteristik_id' => $this->karakteristik?->karakteristik_id,
                'nama_karakteristik' => $this->karakteristik?->nama_karakteristik,
            ]),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
