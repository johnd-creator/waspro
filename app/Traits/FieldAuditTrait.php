<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait FieldAuditTrait
{
    /**
     * Boot the trait
     */
    protected static function bootFieldAuditTrait(): void
    {
        // Listen for model events
        static::created(function (Model $model) {
            static::logCreation($model);
        });

        static::updated(function (Model $model) {
            static::logUpdate($model);
        });

        static::deleted(function (Model $model) {
            static::logDeletion($model);
        });
    }

    /**
     * Log model creation
     */
    protected static function logCreation(Model $model): void
    {
        if (!static::shouldAudit($model, 'create')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_value' => null,
            'new_value' => $model->getAttributes(),
            'old_value_simple' => null,
            'new_value_simple' => json_encode($model->getAttributes()),
            'business_context' => static::getBusinessContext($model, 'create'),
            'reason' => static::getReason($model, 'create'),
            'session_id' => session()->getId(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    /**
     * Log model update
     */
    protected static function logUpdate(Model $model): void
    {
        if (!static::shouldAudit($model, 'update')) {
            return;
        }

        $changes = $model->getDirty();
        
        if (empty($changes)) {
            return;
        }

        // Log each field change separately for field-level tracking
        foreach ($changes as $field => $newValue) {
            $oldValue = $model->getOriginal($field);
            
            // Skip if no actual change
            if ($oldValue === $newValue) {
                continue;
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'table_name' => $model->getTable(),
                'field_name' => $field,
                'record_id' => $model->getKey(),
                'old_value' => [$field => $oldValue],
                'new_value' => [$field => $newValue],
                'old_value_simple' => static::formatSimpleValue($oldValue),
                'new_value_simple' => static::formatSimpleValue($newValue),
                'business_context' => static::getBusinessContext($model, 'update', $field),
                'reason' => static::getReason($model, 'update', $field),
                'session_id' => session()->getId(),
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        }
    }

    /**
     * Log model deletion
     */
    protected static function logDeletion(Model $model): void
    {
        if (!static::shouldAudit($model, 'delete')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_value' => $model->getAttributes(),
            'new_value' => null,
            'old_value_simple' => json_encode($model->getAttributes()),
            'new_value_simple' => null,
            'business_context' => static::getBusinessContext($model, 'delete'),
            'reason' => static::getReason($model, 'delete'),
            'session_id' => session()->getId(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    /**
     * Determine if the model action should be audited
     */
    protected static function shouldAudit(Model $model, string $action): bool
    {
        // Don't audit if there's no authenticated user
        if (!Auth::check()) {
            return false;
        }

        // Don't audit if user is system/maintenance user
        if (Auth::user()?->email_address === 'system@example.com') {
            return false;
        }

        // Don't audit certain sensitive fields
        $sensitiveFields = ['password', 'kata_sandi_hash', 'remember_token'];
        $changes = $model->getDirty();
        
        foreach ($sensitiveFields as $field) {
            if (isset($changes[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get business context for the audit
     */
    protected static function getBusinessContext(Model $model, string $action, ?string $field = null): ?string
    {
        $tableName = $model->getTable();
        
        return match ($tableName) {
            'log_penyimpanan_limbah' => match ($action) {
                'update' => match ($field) {
                    'status_log' => 'waste_status_change',
                    'expiry_status' => 'expiry_monitoring',
                    'approval_status' => 'approval_workflow',
                    default => 'waste_management'
                },
                'create' => 'waste_recording',
                'delete' => 'waste_removal'
            },
            'pengguna_sistem' => match ($action) {
                'update' => match ($field) {
                    'aktif' => 'user_status_change',
                    'unit_id' => 'user_transfer',
                    default => 'user_management'
                },
                'create' => 'user_creation',
                'delete' => 'user_deletion'
            },
            default => null
        };
    }

    /**
     * Get reason for the change (can be overridden in model)
     */
    protected static function getReason(Model $model, string $action, ?string $field = null): ?string
    {
        // Try to get reason from request input
        $reason = request()?->input('audit_reason');
        
        // Try to get reason from model property
        if (!$reason && isset($model->audit_reason)) {
            $reason = $model->audit_reason;
        }

        // Provide default reasons for common actions
        if (!$reason) {
            $reason = match ($action) {
                'create' => 'New record created',
                'delete' => 'Record deleted',
                'update' => 'Field updated'
            };
        }

        return $reason ? substr($reason, 0, 500) : null;
    }

    /**
     * Format value for simple storage
     */
    protected static function formatSimpleValue($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        if (is_string($value)) {
            return strlen($value) > 200 ? substr($value, 0, 197) . '...' : $value;
        }

        return (string) $value;
    }

    /**
     * Log custom business events
     */
    public static function logBusinessEvent(string $action, string $table, int $recordId, array $data = []): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'old_value' => $data['old_value'] ?? null,
            'new_value' => $data['new_value'] ?? null,
            'old_value_simple' => $data['old_value_simple'] ?? null,
            'new_value_simple' => $data['new_value_simple'] ?? null,
            'business_context' => $data['business_context'] ?? null,
            'reason' => $data['reason'] ?? null,
            'session_id' => session()->getId(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    /**
     * Log approval workflow actions
     */
    public static function logApproval(string $action, string $table, int $recordId, int $approvedBy, ?string $reason = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $approvedBy,
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'business_context' => 'approval_workflow',
            'reason' => $reason,
            'session_id' => session()->getId(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}