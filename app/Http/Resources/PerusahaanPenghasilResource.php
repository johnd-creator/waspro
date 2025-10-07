<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerusahaanPenghasilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'perusahaan_id' => $this->perusahaan_id,
            'nama_perusahaan' => $this->nama_perusahaan,
            'jenis_perusahaan' => $this->jenis_perusahaan,
            'npwp' => $this->npwp,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'kota' => $this->kota,
            'alamat_perusahaan' => $this->alamat_perusahaan,
            'person_in_charge' => $this->person_in_charge,
            'status_aktif' => (bool) $this->status_aktif,
            'keterangan' => $this->keterangan,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
