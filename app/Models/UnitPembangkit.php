<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPembangkit extends Model
{
    use HasFactory;

    protected $table = 'unit_pembangkit';
    protected $primaryKey = 'unit_id';
    
    protected $fillable = [
        'nama_unit',
        'alamat_unit',
        'kota',
        'kode_pos',
    ];

    /**
     * Get all users for this unit
     */
    public function penggunaSistem(): HasMany
    {
        return $this->hasMany(PenggunaSistem::class, 'unit_id', 'unit_id');
    }

    /**
     * Get all logs for this unit
     */
    public function logPenyimpanan(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'unit_id', 'unit_id');
    }

    /**
     * Get all logs for this unit (alias)
     */
    public function logPenyimpananLimbah(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'unit_id', 'unit_id');
    }
}
