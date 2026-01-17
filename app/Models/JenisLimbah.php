<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisLimbah extends Model
{
    use HasFactory;

    protected $table = 'jenis_limbah';

    protected $primaryKey = 'kode_limbah';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'kode_limbah',
        'nama_limbah',
        'kemasan',
        'jumlah_ton_per_tahun',
        'waktu_penyimpanan_hari',
        'batas_penyimpanan_hari',
        'karakteristik_id',
        'kategori_id',
        'deskripsi_limbah',
        'status_aktif',
        'biaya_pengangkutan_per_kg',
        'mulai_berlaku',
        'akhir_berlaku',
        'keterangan_biaya',
    ];

    protected $casts = [
        'jumlah_ton_per_tahun' => 'integer',
        'waktu_penyimpanan_hari' => 'integer',
        'batas_penyimpanan_hari' => 'integer',
        'status_aktif' => 'boolean',
        'mulai_berlaku' => 'date',
        'akhir_berlaku' => 'date',
        'biaya_pengangkutan_per_kg' => 'decimal:2',
        'keterangan_biaya' => 'array',
    ];

    /**
     * Get the karakteristik that owns the jenis limbah
     */
    public function karakteristik(): BelongsTo
    {
        return $this->belongsTo(KarakteristikLimbah::class, 'karakteristik_id', 'karakteristik_id');
    }

    /**
     * Get karakteristik limbah for this jenis limbah (alias)
     */
    public function karakteristikLimbah(): BelongsTo
    {
        return $this->belongsTo(KarakteristikLimbah::class, 'karakteristik_id', 'karakteristik_id');
    }

    /**
     * Get kategori kegiatan sumber that owns jenis limbah
     */
    public function kategoriKegiatanSumber(): BelongsTo
    {
        return $this->belongsTo(KategoriKegiatanSumber::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Get all logs for this jenis limbah
     */
    public function logPenyimpanan(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'kode_limbah', 'kode_limbah');
    }
}
