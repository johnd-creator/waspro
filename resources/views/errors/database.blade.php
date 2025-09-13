@extends('layouts.app')

@section('title', 'Database Error')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                <i class="fas fa-database text-4xl text-red-600"></i>
            </div>
            
            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Database Error
            </h1>
            
            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8">
                {{ $message ?? 'Terjadi kesalahan pada database. Silakan coba lagi nanti.' }}
            </p>
            
            <!-- Error Details for Development -->
            @if(config('app.debug') && isset($exception))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Error Details:</h3>
                    <p class="text-xs text-red-700 font-mono break-all">
                        {{ $exception->getMessage() }}
                    </p>
                    @if(method_exists($exception, 'getSql'))
                        <p class="text-xs text-red-700 font-mono mt-2">
                            <strong>SQL:</strong> {{ $exception->getSql() }}
                        </p>
                    @endif
                </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.history.back()" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
                
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    Coba Lagi
                </button>
            </div>
            
            <!-- Help Text -->
            <div class="mt-8 text-sm text-gray-500">
                <p>Jika masalah ini terus berlanjut, silakan hubungi administrator sistem.</p>
                <p class="mt-2">
                    <i class="fas fa-clock mr-1"></i>
                    Error ID: {{ Str::random(8) }} - {{ now()->format('Y-m-d H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</div>

@if(config('app.debug'))
<script>
// Auto-refresh after 30 seconds if in development mode
setTimeout(function() {
    if (confirm('Halaman akan di-refresh otomatis. Lanjutkan?')) {
        window.location.reload();
    }
}, 30000);
</script>
@endif
@endsection