<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitPembangkitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'unit_id' => $this->unit_id,
            'nama_unit' => $this->nama_unit,
            'alamat_unit' => $this->alamat_unit,
            'kota' => $this->kota,
            'kode_pos' => $this->kode_pos,
            'telepon_unit' => $this->telepon_unit,
            'keterangan' => $this->keterangan,
            'status_aktif' => (bool) $this->status_aktif,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
