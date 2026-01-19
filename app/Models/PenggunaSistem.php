<?php

namespace App\Models;

use App\Traits\AuditTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class PenggunaSistem extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use AuditTrait, CanResetPasswordTrait, HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna_sistem';

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email_address',
        'kata_sandi_hash',
        'unit_id',
        'aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'kata_sandi_hash',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'aktif' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->kata_sandi_hash;
    }

    /**
     * Get the email address that should be used for password resets.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email_address;
    }

    // Override the username field for authentication
    public function getAuthIdentifierName()
    {
        return 'email_address';
    }

    /**
     * Get the unit that owns the user
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitPembangkit::class, 'unit_id', 'unit_id');
    }

    // Relationship with UnitPembangkit
    public function unitPembangkit()
    {
        return $this->belongsTo(UnitPembangkit::class, 'unit_id', 'unit_id');
    }

    /**
     * The roles that belong to the user
     */
    public function peran(): BelongsToMany
    {
        return $this->belongsToMany(PeranPengguna::class, 'pengguna_peran', 'user_id', 'peran_id')
            ->select('peran_pengguna.*')
            ->withTimestamps();
    }

    // Many-to-many relationship with PeranPengguna
    public function peranPengguna()
    {
        return $this->belongsToMany(PeranPengguna::class, 'pengguna_peran', 'user_id', 'peran_id')
            ->select('peran_pengguna.*');
    }

    /**
     * Get all logs created by this user
     */
    public function logPenyimpanan(): HasMany
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'user_id', 'user_id');
    }

    // Relationship with LogPenyimpananLimbah
    public function logPenyimpananLimbah()
    {
        return $this->hasMany(LogPenyimpananLimbah::class, 'user_id', 'user_id');
    }

    /**
     * Scope untuk filter berdasarkan unit
     */
    public function scopeByUnit(Builder $query, $unitId = null)
    {
        if ($unitId) {
            return $query->where('unit_id', $unitId);
        }

        return $query;
    }

    /**
     * Scope untuk filter berdasarkan unit user yang sedang login
     */
    public function scopeByCurrentUserUnit(Builder $query)
    {
        $currentUser = Auth::user();

        if ($currentUser && ! $this->isUserAdmin($currentUser)) {
            return $query->where('unit_id', $currentUser->unit_id);
        }

        return $query;
    }

    /**
     * Scope untuk filter hanya user aktif
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk filter berdasarkan peran
     */
    public function scopeByPeran(Builder $query, $peranName)
    {
        return $query->whereHas('peranPengguna', function ($q) use ($peranName) {
            $q->where('nama_peran', $peranName);
        });
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin()
    {
        return $this->peranPengguna()->where('peran_pengguna.nama_peran', 'Super Admin')->exists();
    }

    /**
     * Check if user is Administrator
     */
    public function isAdministrator()
    {
        return $this->peranPengguna()->where('peran_pengguna.nama_peran', 'Administrator')->exists();
    }

    /**
     * Check if user is Operator
     */
    public function isOperator()
    {
        return $this->peranPengguna()->where('peran_pengguna.nama_peran', 'Operator')->exists();
    }

    /**
     * Check if user is Viewer
     */
    public function isViewer()
    {
        return $this->peranPengguna()->where('peran_pengguna.nama_peran', 'Viewer')->exists();
    }

    /**
     * Check if user is Supervisor
     */
    public function isSupervisor()
    {
        return $this->peranPengguna()->where('peran_pengguna.nama_peran', 'Supervisor')->exists();
    }

    /**
     * Check if user is Admin (backward compatibility)
     * Now includes Administrator and Super Admin roles
     */
    public function isAdmin()
    {
        $adminRoles = ['Admin', 'Administrator', 'Super Admin'];

        return $this->peranPengguna()->whereIn('peran_pengguna.nama_peran', $adminRoles)->exists();
    }

    /**
     * Cek apakah user tertentu adalah admin (static method)
     */
    public static function isUserAdmin($user)
    {
        if (! $user) {
            return false;
        }

        return $user->peranPengguna()->whereIn('peran_pengguna.nama_peran', ['Admin', 'Super Admin'])->exists();
    }

    /**
     * Cek apakah user dapat mengakses unit tertentu
     */
    public function canAccessUnit($unitId)
    {
        // Admin dapat mengakses semua unit
        if ($this->isAdmin()) {
            return true;
        }

        // User biasa hanya dapat mengakses unit sendiri
        return $this->unit_id == $unitId;
    }

    /**
     * Get nama peran sebagai string
     */
    public function getPeranNamesAttribute()
    {
        return $this->peranPengguna()->pluck('peran_pengguna.nama_peran')->join(', ');
    }

    /**
     * Get status aktif sebagai string
     */
    public function getStatusTextAttribute()
    {
        return $this->aktif ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get nama unit
     */
    public function getUnitNameAttribute()
    {
        return $this->unitPembangkit ? $this->unitPembangkit->nama_unit : 'N/A';
    }

    /**
     * Boot method untuk menambahkan global scope jika diperlukan
     */
    protected static function boot()
    {
        parent::boot();

        // Uncomment jika ingin menambahkan global scope untuk membatasi akses berdasarkan unit
        // static::addGlobalScope('unit', function (Builder $builder) {
        //     $currentUser = Auth::user();
        //     if ($currentUser && !self::isUserAdmin($currentUser)) {
        //         $builder->where('unit_id', $currentUser->unit_id);
        //     }
        // });
    }
}
