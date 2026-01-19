@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--text-primary);">Audit Trail</h1>
            <p class="mt-1" style="color: var(--text-secondary);">Track all system activities and changes</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('audit-log.export.csv') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 003-3v-1m-3-4v-4m0 5h15m-9 2v1a3 3 0 003 3h15a3 3 0 003-3v-1m-9-2h8m-3-4v4h.01M5 15h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v7z"></path>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('audit-log.export.excel') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a3 3 0 003 3h15a3 3 0 003-3v-1m-3-4v-4m0 5h15m-9 2v1a3 3 0 003 3h6a3 3 0 003-3v-1m-9-2h8m-3-4v4h.01M5 15h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v7z"></path>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <div class="rounded-lg shadow-md p-6 mb-6"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <form method="GET" action="{{ route('audit-log.index') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">User</label>
                    <select name="user_id" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                                {{ $user->nama_lengkap }} ({{ $user->email_address }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Action</label>
                    <select name="action" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Table</label>
                    <select name="table_name" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">All Tables</option>
                        @foreach($tables as $table)
                            <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>
                                {{ $table }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Business Context</label>
                    <select name="business_context" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">All Contexts</option>
                        @foreach($contexts as $context)
                            <option value="{{ $context }}" {{ request('business_context') == $context ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $context)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none transition-colors"
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('audit-log.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-lg shadow-md overflow-hidden"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y" style="border-color: var(--border-primary);">
                <thead class="rounded-t-lg" style="background-color: var(--border-primary);">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Field</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Old Value</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">New Value</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Context</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">IP Address</th>
                    </tr>
                </thead>
                <tbody style="background-color: var(--card-bg);">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 border-b" style="border-color: var(--border-primary);">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--text-primary);">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--text-secondary);">
                                {{ $log->user ? $log->user->nama_lengkap : 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $log->action_text ?? ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--text-secondary);">
                                {{ $log->table_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--text-secondary);">
                                {{ $log->field_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm max-w-xs truncate" style="color: var(--text-secondary);">
                                {{ Str::limit($log->old_value_simple ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 text-sm max-w-xs truncate" style="color: var(--text-secondary);">
                                {{ Str::limit($log->new_value_simple ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->business_context)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->context_badge_class ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $log->context_text ?? ucfirst(str_replace('_', ' ', $log->business_context)) }}
                                    </span>
                                @else
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--text-secondary);">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full"
                                         style="background-color: var(--hover-bg);">
                                        <i class="fas fa-history text-4xl"
                                           style="color: var(--text-tertiary);"></i>
                                    </div>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada data</h3>
                                    <p class="text-sm" style="color: var(--text-secondary);">No audit logs found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@if(request()->hasAny(['user_id', 'action', 'table_name', 'business_context', 'start_date', 'end_date']))
<div class="fixed bottom-4 right-4 rounded-lg shadow-lg p-4 max-w-sm"
     style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
    <h3 class="font-semibold mb-3" style="color: var(--text-primary);">Active Filters</h3>
    <ul class="space-y-2 text-sm" style="color: var(--text-secondary);">
        @if(request('user_id'))
            <li><strong style="color: var(--text-primary);">User:</strong> {{ $users->find(request('user_id'))->nama_lengkap ?? 'Unknown' }}</li>
        @endif
        
        @if(request('action'))
            <li><strong style="color: var(--text-primary);">Action:</strong> {{ ucfirst(request('action')) }}</li>
        @endif
        
        @if(request('table_name'))
            <li><strong style="color: var(--text-primary);">Table:</strong> {{ request('table_name') }}</li>
        @endif
        
        @if(request('business_context'))
            <li><strong style="color: var(--text-primary);">Context:</strong> {{ ucfirst(str_replace('_', ' ', request('business_context'))) }}</li>
        @endif
        
        @if(request('start_date'))
            <li><strong style="color: var(--text-primary);">From:</strong> {{ request('start_date') }}</li>
        @endif
        
        @if(request('end_date'))
            <li><strong style="color: var(--text-primary);">To:</strong> {{ request('end_date') }}</li>
        @endif
    </ul>
</div>
@endif
@endsection
