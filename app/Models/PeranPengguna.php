<?php

namespace App\Models;

use App\Traits\AuditTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PeranPengguna extends Model
{
    use AuditTrait, HasFactory;

    protected $table = 'peran_pengguna';

    protected $primaryKey = 'peran_id';

    protected $fillable = [
        'nama_peran',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Toggle the active status of the role.
     */
    public function toggleStatus()
    {
        $this->is_active = ! $this->is_active;
        $this->save();

        return $this;
    }

    /**
     * The users that belong to the role
     */
    public function penggunaSistem(): BelongsToMany
    {
        return $this->belongsToMany(PenggunaSistem::class, 'pengguna_peran', 'peran_id', 'user_id')
            ->withTimestamps();
    }
}
