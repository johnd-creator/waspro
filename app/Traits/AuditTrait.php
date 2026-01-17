<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait AuditTrait
{
    protected static function bootAudit(): void
    {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity(string $action, Model $model): void
    {
        $userId = null;
        if (auth()->guard('web')->check()) {
            $userId = auth()->guard('web')->id();
        }

        $data = [
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_value' => null,
            'new_value' => $model->getAttributes(),
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
        ];

        if ($action === 'update') {
            $data['old_value'] = $model->getRawOriginal();
        }

        AuditLog::create($data);

        Log::info('Audit Activity', array_merge($data, ['user_id' => $userId]));
    }
}
