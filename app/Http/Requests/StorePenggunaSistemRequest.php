<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\PeranPengguna;
use App\Models\PenggunaSistem;

class StorePenggunaSistemRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;
    public $failedValidation = false;

    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'email_address' => 'required|email|unique:pengguna_sistem,email_address',
            'kata_sandi' => 'required|string|min:8|confirmed',
            'unit_id' => 'nullable|exists:unit_pembangkit,unit_id',
            'peran_ids' => 'required|array|min:1',
            'peran_ids.*' => 'exists:peran_pengguna,peran_id',
            'aktif' => 'boolean',
        ];

        $currentUser = Auth::guard('web')->user();
        $isSuperAdminRole = in_array('Super Admin', $this->input('peran_ids', []));

        if ($isSuperAdminRole) {
            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;

            if ($superAdminPeranId) {
                $existingSuperAdmin = PenggunaSistem::whereHas('peranPengguna', function ($q) use ($superAdminPeranId) {
                    $q->where('peran_pengguna.peran_id', $superAdminPeranId);
                })->exists();

                if ($existingSuperAdmin) {
                    $this->failedValidation = true;
                    return [];
                }

                $rules['peran_ids'] = [
                    'required',
                    'array',
                    Rule::in([$superAdminPeranId]),
                ];

                if ($currentUser && !$this->isAdmin($currentUser)) {
                    $this->failedValidation = true;
                    return [];
                }
            }
        } else {
            $rules['unit_id'] = 'required|exists:unit_pembangkit,unit_id';

            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;
            $rules['peran_ids.*'] = function ($attribute, $value, $fail) use ($superAdminPeranId) {
                if ($value == $superAdminPeranId) {
                    $fail('Hanya satu Super Admin yang diizinkan.');
                }
            };

            if ($currentUser && !$this->isAdmin($currentUser)) {
                $rules['unit_id'] = [
                    'required',
                    'exists:unit_pembangkit,unit_id',
                    Rule::in([$currentUser->unit_id]),
                ];
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->failedValidation ?? false) {
                if (in_array('Super Admin', $this->input('peran_ids', []))) {
                    $existingSuperAdmin = PenggunaSistem::whereHas('peranPengguna', function ($q) {
                        $q->where('peran_pengguna.nama_peran', 'Super Admin');
                    })->exists();

                    if ($existingSuperAdmin) {
                        $validator->errors()->add('peran_ids', 'Hanya satu Super Admin yang diizinkan.');
                    }
                }

                $currentUser = Auth::guard('web')->user();
                if ($currentUser && !$this->isAdmin($currentUser)) {
                    $validator->errors()->add('peran_ids', 'Anda tidak memiliki izin untuk membuat Super Admin.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'email_address.required' => 'Email harus diisi.',
            'email_address.email' => 'Format email tidak valid.',
            'email_address.unique' => 'Email sudah digunakan.',
            'kata_sandi.required' => 'Kata sandi harus diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'unit_id.required' => 'Unit harus dipilih.',
            'unit_id.exists' => 'Unit tidak valid.',
            'peran_ids.required' => 'Peran harus dipilih.',
            'peran_ids.min' => 'Minimal satu peran harus dipilih.',
            'peran_ids.in' => 'Peran tidak valid.',
        ];
    }

    protected function isAdmin($user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->peranPengguna()->where('nama_peran', 'Super Admin')->exists() ||
               $user->peranPengguna()->where('nama_peran', 'Administrator')->exists();
    }
}
