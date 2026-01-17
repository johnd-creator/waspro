<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PenggunaSistem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AuditLogExport;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()
            ->with(['user', 'approvedBy'])
            ->byOrgScope();

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('table_name')) {
            $query->byTable($request->table_name);
        }

        if ($request->filled('business_context')) {
            $query->byContext($request->business_context);
        }

        if ($request->filled('field_level')) {
            $query->fieldLevel($request->boolean('field_level'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get filter options
        $currentUser = Auth::user();
        $usersQuery = PenggunaSistem::where('aktif', true);
        
        if ($currentUser && !$currentUser->isSuperAdmin()) {
            $usersQuery->where('unit_id', $currentUser->unit_id);
        }
        
        $users = $usersQuery->orderBy('nama_lengkap')->get();

        $tables = AuditLog::select('table_name')
            ->distinct()
            ->orderBy('table_name')
            ->pluck('table_name');

        $actions = ['create', 'update', 'delete', 'approve', 'reject'];
        $contexts = [
            'waste_management', 'waste_status_change', 'expiry_monitoring',
            'approval_workflow', 'user_management', 'user_status_change'
        ];

        return view('audit-log.index', compact('logs', 'users', 'tables', 'actions', 'contexts'));
    }

    public function exportCsv(Request $request)
    {
        $query = AuditLog::query()
            ->with(['user', 'approvedBy'])
            ->byOrgScope();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('table_name')) {
            $query->byTable($request->table_name);
        }

        if ($request->filled('business_context')) {
            $query->byContext($request->business_context);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'audit-trail-'.now()->format('Y-m-d_H-i-s').'.csv';
        
        $csvHeaders = [
            'Timestamp',
            'User Name',
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
        ];

        $callback = function() use ($logs, $csvHeaders) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $csvHeaders);

            foreach ($logs as $log) {
                $row = [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->nama_lengkap : 'System',
                    $log->action,
                    $log->table_name,
                    $log->field_name ?? '',
                    $log->record_id,
                    $this->formatAuditValue($log->old_value_simple),
                    $this->formatAuditValue($log->new_value_simple),
                    $log->business_context ?? '',
                    $log->reason ?? '',
                    $log->ip_address ?? '',
                    $log->session_id ?? '',
                ];
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = AuditLog::query()
            ->with(['user', 'approvedBy'])
            ->byOrgScope();

        // Apply same filters as CSV export
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('table_name')) {
            $query->byTable($request->table_name);
        }

        if ($request->filled('business_context')) {
            $query->byContext($request->business_context);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'audit-trail-'.now()->format('Y-m-d_H-i-s').'.xlsx';
        
        return Excel::download(new AuditLogExport($logs), $filename);
    }

    /**
     * Format audit value for export
     */
    private function formatAuditValue($value): string
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
