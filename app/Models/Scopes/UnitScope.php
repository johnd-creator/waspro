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
        
        // Jika user login dan bukan Super Admin, filter berdasarkan unit
        if ($user && !$this->isSuperAdmin($user)) {
            $builder->where($model->getTable() . '.unit_id', $user->unit_id);
        }
        
        // Jika ada user_unit_id di request (dari middleware), gunakan itu
        if (request()->has('user_unit_id')) {
            $builder->where($model->getTable() . '.unit_id', request()->get('user_unit_id'));
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