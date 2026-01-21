<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'approval_log';

    protected $fillable = [
        'log_id',
        'approved_by',
        'action',
        'rejected_reason',
        'status_sebelumnya',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function logPenyimpanan(): BelongsTo
    {
        return $this->belongsTo(LogPenyimpananLimbah::class, 'log_id', 'log_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(PenggunaSistem::class, 'approved_by', 'user_id');
    }
}
