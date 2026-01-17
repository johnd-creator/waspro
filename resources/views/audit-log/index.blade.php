@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Audit Trail</h1>
            <p class="text-gray-600 mt-1">Track all system activities and changes</p>
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

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('audit-log.index') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                                {{ $user->nama_lengkap }} ({{ $user->email_address }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Table Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Table</label>
                    <select name="table_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Tables</option>
                        @foreach($tables as $table)
                            <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>
                                {{ $table }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Business Context Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Context</label>
                    <select name="business_context" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Contexts</option>
                        @foreach($contexts as $context)
                            <option value="{{ $context }}" {{ request('business_context') == $context ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $context)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Apply Filters
                </button>
                <a href="{{ route('audit-log.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Context</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->user ? $log->user->nama_lengkap : 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $log->action_text ?? ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->table_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->field_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ Str::limit($log->old_value_simple ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ Str::limit($log->new_value_simple ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->business_context)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->context_badge_class ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $log->context_text ?? ucfirst(str_replace('_', ' ', $log->business_context)) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                No audit logs found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<!-- Active Filters Display -->
@if(request()->hasAny(['user_id', 'action', 'table_name', 'business_context', 'start_date', 'end_date']))
<div class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 max-w-sm">
    <h3 class="font-semibold text-gray-900 mb-3">Active Filters</h3>
    <ul class="space-y-2 text-sm text-gray-600">
        @if(request('user_id'))
            <li><strong>User:</strong> {{ $users->find(request('user_id'))->nama_lengkap ?? 'Unknown' }}</li>
        @endif
        
        @if(request('action'))
            <li><strong>Action:</strong> {{ ucfirst(request('action')) }}</li>
        @endif
        
        @if(request('table_name'))
            <li><strong>Table:</strong> {{ request('table_name') }}</li>
        @endif
        
        @if(request('business_context'))
            <li><strong>Context:</strong> {{ ucfirst(str_replace('_', ' ', request('business_context'))) }}</li>
        @endif
        
        @if(request('start_date'))
            <li><strong>From:</strong> {{ request('start_date') }}</li>
        @endif
        
        @if(request('end_date'))
            <li><strong>To:</strong> {{ request('end_date') }}</li>
        @endif
    </ul>
</div>
@endif
@endsection
