<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingsAuditController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->where('table_name', 'application_settings')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('settings.audit', compact('logs'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return view('settings.audit-detail', compact('log'));
    }
}
