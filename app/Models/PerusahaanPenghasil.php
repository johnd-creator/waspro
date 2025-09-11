<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerusahaanPenghasil extends Model
{
    use HasFactory;

    protected $table = 'perusahaan_penghasil';
    protected $primaryKey = 'perusahaan_id';
    
    protected $fillable = [
        'nama_perusahaan',
        'jenis_perusahaan',
        'telepon',
        'email',
        'kota',
        'alamat_perusahaan',
        'person_in_charge',
        'status_aktif',
        'keterangan',
    ];

    /**
     * Get all logs for this perusahaan
     */
    public function logPenyimpanan(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'perusahaan_id', 'perusahaan_id');
    }

    /**
     * Get all logs for this perusahaan (alias)
     */
    public function logPenyimpananLimbah(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'perusahaan_id', 'perusahaan_id');
    }
}
