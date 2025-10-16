<?php

namespace App\Models;

use App\Models\Scopes\UnitScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class LogPenyimpananLimbah extends Model
{
    use HasFactory;

    protected $table = 'log_penyimpanan_limbah';

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'kode_identitas',
        'tanggal_limbah_masuk',
        'detail_sumber_limbah',
        'jumlah_limbah_masuk',
        'maksimal_penyimpanan_tanggal',
        'status_log',
        'tanggal_pengangkutan',
        'jumlah_diangkut',
        'user_id',
        'kode_limbah',
        'perusahaan_id',
        'unit_id',
        'tanggal_kadaluarsa',
        'expiry_status',
        'dokumen_path',
        'dokumen_original_name',
        'dokumen_mime',
        'dokumen_size',
        'dokumen_uploaded_at',
        // Offline sync columns
        'client_uuid',
        'created_at_client',
        'updated_at_client',
        'synced_at',
    ];

    protected $casts = [
        'tanggal_limbah_masuk' => 'datetime',
        'maksimal_penyimpanan_tanggal' => 'datetime',
        'tanggal_pengangkutan' => 'datetime',
        'tanggal_kadaluarsa' => 'datetime',
        'dokumen_uploaded_at' => 'datetime',
        'created_at_client' => 'datetime',
        'updated_at_client' => 'datetime',
        'synced_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new UnitScope);

        static::creating(function ($log) {
            if (empty($log->kode_identitas)) {
                $log->kode_identitas = self::generateKodeIdentitas($log->unit_id);
            }
        });
    }

    /**
     * Generate unique waste identification code
     */
    private static function generateKodeIdentitas($unitId)
    {
        $unit = UnitPembangkit::find($unitId);
        $unitCode = $unit ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $unit->nama_unit), 0, 4)) : 'UNIT';
        $yearMonth = date('Ym');

        $lastSequence = self::withoutGlobalScope(UnitScope::class)
            ->where('kode_identitas', 'LIKE', "LMB-{$unitCode}-{$yearMonth}-%")
            ->orderBy('kode_identitas', 'desc')
            ->first();

        $sequence = $lastSequence ? intval(substr($lastSequence->kode_identitas, -3)) + 1 : 1;

        return "LMB-{$unitCode}-{$yearMonth}-".str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user that created this log
     */
    public function penggunaSistem(): BelongsTo
    {
        return $this->belongsTo(PenggunaSistem::class, 'user_id', 'user_id');
    }

    /**
     * Get the jenis limbah for this log
     */
    public function jenisLimbah(): BelongsTo
    {
        return $this->belongsTo(JenisLimbah::class, 'kode_limbah', 'kode_limbah');
    }

    /**
     * Get the perusahaan for this log
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(PerusahaanPenghasil::class, 'perusahaan_id', 'perusahaan_id');
    }

    /**
     * Get the perusahaan penghasil for this log (alias)
     */
    public function perusahaanPenghasil(): BelongsTo
    {
        return $this->belongsTo(PerusahaanPenghasil::class, 'perusahaan_id', 'perusahaan_id');
    }

    /**
     * Get the unit for this log
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitPembangkit::class, 'unit_id', 'unit_id');
    }

    /**
     * Get the unit pembangkit for this log (alias)
     */
    public function unitPembangkit(): BelongsTo
    {
        return $this->belongsTo(UnitPembangkit::class, 'unit_id', 'unit_id');
    }

    /**
     * Calculate expiry date based on entry date and waste type storage days
     */
    public function calculateExpiryDate(): ?Carbon
    {
        if (! $this->tanggal_limbah_masuk) {
            return null;
        }

        $expiryDays = $this->getExpiryDaysSetting();

        return Carbon::parse($this->tanggal_limbah_masuk)->addDays($expiryDays);
    }

    /**
     * Update expiry status based on current date
     */
    public function updateExpiryStatus(): void
    {
        $expiryDate = $this->calculateExpiryDate();
        if (! $expiryDate) {
            return;
        }

        $now = Carbon::now();
        $daysUntilExpiry = $now->diffInDays($expiryDate, false);

        if ($daysUntilExpiry < 0) {
            $status = 'Expired';
        } elseif ($daysUntilExpiry <= 3) {
            $status = 'Critical';
        } elseif ($daysUntilExpiry <= 7) {
            $status = 'Warning';
        } else {
            $status = 'Safe';
        }

        $this->update([
            'tanggal_kadaluarsa' => $expiryDate,
            'expiry_status' => $status,
        ]);
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiry(): ?int
    {
        $expiryDate = $this->tanggal_kadaluarsa ? Carbon::parse($this->tanggal_kadaluarsa) : $this->calculateExpiryDate();
        if (! $expiryDate) {
            return null;
        }

        return Carbon::now()->diffInDays($expiryDate, false);
    }

    /**
     * Get expiry days from waste type or fallback to app_settings
     */
    private function getExpiryDaysSetting(): int
    {
        // First, try to get from jenis limbah waktu_penyimpanan_hari
        if ($this->jenisLimbah && $this->jenisLimbah->waktu_penyimpanan_hari) {
            return (int) $this->jenisLimbah->waktu_penyimpanan_hari;
        }

        // Fallback to global setting
        $setting = DB::table('app_settings')
            ->where('key', 'limbah_expiry_days')
            ->first();

        return $setting ? (int) $setting->value : 90;
    }

    /**
     * Scope for filtering by expiry status
     */
    public function scopeByExpiryStatus($query, $status)
    {
        return $query->where('expiry_status', $status);
    }

    /**
     * Scope for expired waste
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_status', 'Expired');
    }

    /**
     * Scope for critical waste (expires in 3 days or less)
     */
    public function scopeCritical($query)
    {
        return $query->where('expiry_status', 'Critical');
    }

    /**
     * Scope for warning waste (expires in 7 days or less)
     */
    public function scopeWarning($query)
    {
        return $query->where('expiry_status', 'Warning');
    }

    /**
     * Scope for safe waste
     */
    public function scopeSafe($query)
    {
        return $query->where('expiry_status', 'Safe');
    }

    /**
     * Get expiry status badge class for UI
     */
    public function getStatusLogBadgeClass(): string
    {
        return match (strtoupper($this->status_log)) {
            'TERSIMPAN' => 'bg-blue-100 text-blue-800',
            'DIANGKUT' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get expiry status text for display
     */
    public function getStatusLogText(): string
    {
        return match (strtoupper($this->status_log)) {
            'TERSIMPAN' => 'Tersimpan',
            'DIANGKUT' => 'Diangkut',
            default => 'Unknown',
        };
    }

    /**
     * Get expiry status badge class for UI
     */
    public function getExpiryStatusBadgeClass(): string
    {
        return match (ucfirst(strtolower($this->expiry_status))) {
            'Safe' => 'bg-green-100 text-green-800',
            'Warning' => 'bg-yellow-100 text-yellow-800',
            'Critical' => 'bg-orange-100 text-orange-800',
            'Expired' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get expiry status text for display
     */
    public function getExpiryStatusText(): string
    {
        return match (ucfirst(strtolower($this->expiry_status))) {
            'Safe' => 'Aman',
            'Warning' => 'Peringatan',
            'Critical' => 'Kritis',
            'Expired' => 'Kadaluarsa',
            default => 'Unknown',
        };
    }
}
