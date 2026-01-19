<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class StoreJenisLimbahRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'kode_limbah' => 'required|string|max:10|unique:jenis_limbah,kode_limbah',
            'nama_limbah' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'deskripsi_limbah' => 'nullable|string|max:500',
            'waktu_penyimpanan_hari' => 'required|integer|min:1|max:365',
            'karakteristik_id' => 'required|exists:karakteristik_limbah,karakteristik_id',
            'kategori_id' => 'required|exists:kategori_kegiatan_sumber,kategori_id',
            'status_aktif' => 'required|boolean',
            'biaya_pengangkutan_per_kg' => 'nullable|numeric|min:0',
            'mulai_berlaku' => 'nullable|date|after_or_equal:today',
            'akhir_berlaku' => 'nullable|date|after:mulai_berlaku',
            'keterangan_biaya' => 'nullable|array',
            'batas_penyimpanan_hari' => 'nullable|integer|min:1|max:365',
        ];
    }

    public function messages()
    {
        return [
            'kode_limbah.required' => 'Kode limbah harus diisi.',
            'kode_limbah.unique' => 'Kode limbah sudah digunakan.',
            'nama_limbah.required' => 'Nama limbah harus diisi.',
            'kemasan.required' => 'Kemasan harus diisi.',
            'waktu_penyimpanan_hari.required' => 'Waktu penyimpanan harus diisi.',
            'waktu_penyimpanan_hari.integer' => 'Waktu penyimpanan harus berupa bilangan bulat.',
            'waktu_penyimpanan_hari.min' => 'Waktu penyimpanan minimal 1 hari.',
            'waktu_penyimpanan_hari.max' => 'Waktu penyimpanan maksimal 365 hari.',
            'karakteristik_id.required' => 'Karakteristik harus dipilih.',
            'karakteristik_id.exists' => 'Karakteristik tidak valid.',
            'kategori_id.required' => 'Kategori harus dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'status_aktif.required' => 'Status harus dipilih.',
            'status_aktif.boolean' => 'Status harus berupa true/false.',
            'biaya_pengangkutan_per_kg.numeric' => 'Biaya pengangkutan harus berupa angka.',
            'biaya_pengangkutan_per_kg.min' => 'Biaya pengangkutan tidak boleh negatif.',
            'mulai_berlaku.date' => 'Format tanggal mulai berlaku tidak valid.',
            'mulai_berlaku.after_or_equal' => 'Tanggal mulai berlaku tidak boleh sebelum hari ini.',
            'akhir_berlaku.date' => 'Format tanggal akhir berlaku tidak valid.',
            'akhir_berlaku.after' => 'Tanggal akhir berlaku harus setelah tanggal mulai.',
            'batas_penyimpanan_hari.integer' => 'Batas penyimpanan harus berupa bilangan bulat.',
            'batas_penyimpanan_hari.min' => 'Batas penyimpanan minimal 1 hari.',
            'batas_penyimpanan_hari.max' => 'Batas penyimpanan maksimal 365 hari.',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                $validated = $validator->getData();
                if (isset($validated['batas_penyimpanan_hari']) && !empty($validated['batas_penyimpanan_hari'])) {
                    $validator->setData(array_merge($validated, ['waktu_penyimpanan_hari' => $validated['batas_penyimpanan_hari']]));
                }
            }
        });
    }
}
