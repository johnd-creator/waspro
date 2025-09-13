<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriKegiatanSumber extends Model
{
    use HasFactory;

    protected $table = 'kategori_kegiatan_sumber';

    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Get all log penyimpanan limbah for this kategori
     */
    public function logPenyimpananLimbah(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'detail_sumber_limbah', 'nama_kategori');
    }
}
