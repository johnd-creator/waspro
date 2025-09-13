<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UnitScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::guard('web')->user();

        // Debug logging untuk troubleshoot masalah setelah navigasi
        \Log::info('UnitScope Debug', [
            'model' => get_class($model),
            'user_exists' => $user ? true : false,
            'user_id' => $user ? $user->user_id : null,
            'unit_id' => $user ? $user->unit_id : null,
            'is_super_admin' => $user ? $this->isSuperAdmin($user) : false,
            'request_url' => request()->url(),
            'request_has_user_unit_id' => request()->has('user_unit_id'),
            'session_id' => session()->getId(),
        ]);

        // Jika user login dan bukan Super Admin, filter berdasarkan unit
        if ($user && ! $this->isSuperAdmin($user) && $user->unit_id) {
            $builder->where($model->getTable().'.unit_id', $user->unit_id);
            \Log::info('UnitScope Applied Filter', [
                'table' => $model->getTable(),
                'unit_id' => $user->unit_id,
            ]);
        } else {
            \Log::info('UnitScope No Filter Applied', [
                'reason' => ! $user ? 'no_user' : (! $this->isSuperAdmin($user) ? 'not_super_admin' : 'is_super_admin'),
                'user_has_unit_id' => $user ? ($user->unit_id ? true : false) : false,
            ]);
        }
        // Jika user tidak memiliki unit_id atau Super Admin, tampilkan semua data

        // Jika ada user_unit_id di request (dari middleware), gunakan itu
        if (request()->has('user_unit_id')) {
            $builder->where($model->getTable().'.unit_id', request()->get('user_unit_id'));
            \Log::info('UnitScope Applied Request Filter', [
                'user_unit_id' => request()->get('user_unit_id'),
            ]);
        }
    }

    /**
     * Check if user is Super Admin (can access all units)
     */
    private function isSuperAdmin($user)
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Super Admin')->exists();
    }

    /**
     * Check if user is admin (backward compatibility)
     */
    private function isAdmin($user)
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Admin')->exists() ||
               $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Administrator')->exists() ||
               $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Super Admin')->exists();
    }
}
