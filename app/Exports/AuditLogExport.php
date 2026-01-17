<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Timestamp',
            'User Name',
            'Email',
            'Action',
            'Table Name',
            'Field Name',
            'Record ID',
            'Old Value',
            'New Value',
            'Business Context',
            'Reason',
            'IP Address',
            'Session ID',
            'User Agent',
        ];
    }

    /**
     * @param mixed $log
     * @return array
     */
    public function map($log): array
    {
        return [
            $log->created_at->format('Y-m-d H:i:s'),
            $log->user ? $log->user->nama_lengkap : 'System',
            $log->user ? $log->user->email_address : 'system@example.com',
            $log->action,
            $log->table_name,
            $log->field_name ?? '',
            $log->record_id,
            $this->formatValue($log->old_value_simple),
            $this->formatValue($log->new_value_simple),
            $log->business_context ?? '',
            $log->reason ?? '',
            $log->ip_address ?? '',
            $log->session_id ?? '',
            $log->user_agent ?? '',
        ];
    }

    private function formatValue($value): string
    {
        if (is_null($value)) {
            return '';
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return (string) $value;
    }
}
