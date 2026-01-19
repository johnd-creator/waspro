<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePerusahaanPenghasilRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'nama_perusahaan' => 'required|string|max:255|unique:perusahaan_penghasil,nama_perusahaan',
            'jenis_perusahaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'kota' => 'nullable|string|max:50',
            'alamat_perusahaan' => 'required|string|max:500',
            'person_in_charge' => 'nullable|string|max:100',
            'status_aktif' => 'required|boolean',
            'keterangan' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nama_perusahaan.required' => 'Nama perusahaan harus diisi.',
            'nama_perusahaan.unique' => 'Nama perusahaan sudah digunakan.',
            'jenis_perusahaan.string' => 'Jenis perusahaan harus berupa string.',
            'telepon.string' => 'Telepon harus berupa string.',
            'telepon.max' => 'Telepon maksimal 15 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'kota.string' => 'Kota harus berupa string.',
            'kota.max' => 'Kota maksimal 50 karakter.',
            'alamat_perusahaan.required' => 'Alamat perusahaan harus diisi.',
            'alamat_perusahaan.max' => 'Alamat maksimal 500 karakter.',
            'person_in_charge.string' => 'Person in charge harus berupa string.',
            'person_in_charge.max' => 'Person in charge maksimal 100 karakter.',
            'status_aktif.required' => 'Status aktif harus dipilih.',
            'status_aktif.boolean' => 'Status aktif harus berupa true/false.',
            'keterangan.string' => 'Keterangan harus berupa string.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ];
    }
}
