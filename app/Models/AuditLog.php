<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_log';

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'field_name',
        'record_id',
        'old_value',
        'new_value',
        'old_value_simple',
        'new_value_simple',
        'setting_category',
        'setting_key',
        'old_value_text',
        'new_value_text',
        'business_context',
        'reason',
        'session_id',
        'approved_by',
        'approved_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(PenggunaSistem::class, 'user_id', 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(PenggunaSistem::class, 'approved_by', 'user_id');
    }

    /**
     * Get human readable action text
     */
    public function getActionTextAttribute(): string
    {
        return match($this->action) {
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'approve' => 'Approve',
            'reject' => 'Reject',
            default => ucfirst($this->action)
        };
    }

    /**
     * Get context badge class
     */
    public function getContextBadgeClassAttribute(): string
    {
        return match($this->business_context) {
            'waste_management' => 'bg-blue-100 text-blue-800',
            'waste_status_change' => 'bg-yellow-100 text-yellow-800',
            'expiry_monitoring' => 'bg-orange-100 text-orange-800',
            'approval_workflow' => 'bg-purple-100 text-purple-800',
            'user_management' => 'bg-green-100 text-green-800',
            'user_status_change' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get context text
     */
    public function getContextTextAttribute(): string
    {
        return match($this->business_context) {
            'waste_management' => 'Waste Management',
            'waste_status_change' => 'Waste Status Change',
            'expiry_monitoring' => 'Expiry Monitoring',
            'approval_workflow' => 'Approval Workflow',
            'user_management' => 'User Management',
            'user_status_change' => 'User Status Change',
            default => $this->business_context ? ucfirst(str_replace('_', ' ', $this->business_context)) : 'General'
        };
    }

    /**
     * Scope untuk filter berdasarkan org-scope (unit_id)
     */
    public function scopeByOrgScope($query, $userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }

        $user = PenggunaSistem::find($userId);
        
        // Super Admin dapat melihat semua audit log
        if ($user && $user->isSuperAdmin()) {
            return $query;
        }

        // User lain hanya dapat melihat audit log dari unit-nya sendiri
        if ($user && $user->unit_id) {
            return $query->whereHas('user', function ($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            });
        }

        // Jika tidak ada user atau tidak punya unit, return empty
        return $query->whereRaw('1 = 0');
    }

    /**
     * Scope untuk filter berdasarkan table
     */
    public function scopeByTable($query, $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk field-level audit
     */
    public function scopeFieldLevel($query, $isFieldLevel = true)
    {
        return $isFieldLevel 
            ? $query->whereNotNull('field_name')
            : $query->whereNull('field_name');
    }

    /**
     * Scope untuk business context
     */
    public function scopeByContext($query, $context)
    {
        return $query->where('business_context', $context);
    }
}
