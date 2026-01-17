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
     * Get all jenis limbah for this kategori
     */
    public function jenisLimbah(): HasMany
    {
        return $this->hasMany(JenisLimbah::class, 'kategori_id', 'kategori_id');
    }
}
