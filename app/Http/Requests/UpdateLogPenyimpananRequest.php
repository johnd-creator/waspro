<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLogPenyimpananRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        $rules = [
            'tanggal_limbah_masuk' => 'sometimes|required|date',
            'detail_sumber_limbah' => 'sometimes|required|string',
            'uraian_pekerjaan' => 'nullable|string|max:1000',
            'jumlah_limbah_masuk' => 'sometimes|required|numeric|min:0.01',
            'kode_limbah' => 'sometimes|required|exists:jenis_limbah,kode_limbah',
            'perusahaan_nama' => 'nullable|string|max:255',
            'dokumen_limbah' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ];

        $user = Auth::user();
        if ($user && $user->isSuperAdmin() && empty($user->unit_id)) {
            $rules['unit_id'] = 'sometimes|required|exists:unit_pembangkit,unit_id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'tanggal_limbah_masuk.required' => 'Tanggal limbah masuk harus diisi.',
            'tanggal_limbah_masuk.date' => 'Format tanggal limbah masuk tidak valid.',
            'detail_sumber_limbah.required' => 'Detail sumber limbah harus diisi.',
            'uraian_pekerjaan.max' => 'Uraian pekerjaan maksimal 1000 karakter.',
            'jumlah_limbah_masuk.required' => 'Jumlah limbah masuk harus diisi.',
            'jumlah_limbah_masuk.numeric' => 'Jumlah limbah masuk harus berupa angka.',
            'jumlah_limbah_masuk.min' => 'Jumlah limbah masuk minimal 0.01.',
            'kode_limbah.required' => 'Kode limbah harus diisi.',
            'kode_limbah.exists' => 'Kode limbah tidak ditemukan.',
            'unit_id.required' => 'Unit pembangkit harus dipilih (Super Admin).',
            'unit_id.exists' => 'Unit pembangkit tidak valid.',
            'dokumen_limbah.mimes' => 'Tipe file tidak diizinkan. Hanya: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.',
            'dokumen_limbah.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
