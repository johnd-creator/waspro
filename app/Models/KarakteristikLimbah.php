<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KarakteristikLimbah extends Model
{
    use HasFactory;

    protected $table = 'karakteristik_limbah';
    protected $primaryKey = 'karakteristik_id';
    
    protected $fillable = [
        'nama_karakteristik',
        'status_aktif'
    ];

    /**
     * Get all jenis limbah with this karakteristik
     */
    public function jenisLimbah(): HasMany
    {
        return $this->hasMany(JenisLimbah::class, 'karakteristik_id', 'karakteristik_id');
    }
}
