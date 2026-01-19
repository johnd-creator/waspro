<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreKarakteristikLimbahRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'nama_karakteristik' => 'required|string|max:255|unique:karakteristik_limbah,nama_karakteristik',
            'deskripsi' => 'nullable|string|max:500',
            'warna' => 'nullable|string|max:50',
            'ikon' => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'nama_karakteristik.required' => 'Nama karakteristik harus diisi.',
            'nama_karakteristik.unique' => 'Nama karakteristik sudah digunakan.',
            'deskripsi.string' => 'Deskripsi harus berupa string.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'warna.string' => 'Warna harus berupa string.',
            'warna.max' => 'Warna maksimal 50 karakter.',
            'ikon.string' => 'Ikon harus berupa string.',
            'ikon.max' => 'Ikon maksimal 50 karakter.',
        ];
    }
}
