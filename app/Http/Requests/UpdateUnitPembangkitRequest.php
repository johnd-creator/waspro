<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUnitPembangkitRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        $unitPembangkit = $this->route('unit_pembangkit');

        return [
            'nama_unit' => 'sometimes|required|string|max:255|unique:unit_pembangkit,nama_unit,' . $unitPembangkit->unit_id . ',unit_id',
            'alamat_unit' => 'sometimes|required|string|max:500',
            'kota' => 'sometimes|required|string|max:100',
            'kode_pos' => 'sometimes|required|string|max:10',
        ];
    }

    public function messages()
    {
        return [
            'nama_unit.required' => 'Nama unit harus diisi.',
            'nama_unit.unique' => 'Nama unit sudah digunakan.',
            'alamat_unit.required' => 'Alamat unit harus diisi.',
            'kota.required' => 'Kota harus diisi.',
            'kode_pos.required' => 'Kode POS harus diisi.',
        ];
    }
}
