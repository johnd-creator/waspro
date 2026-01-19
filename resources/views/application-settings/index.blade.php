@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="min-h-screen py-8" style="background-color: var(--bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="rounded-lg shadow-sm border mb-8" style="background-color: var(--card-bg); border-color: var(--border-primary);">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">System Settings</h1>
                        <p class="text-sm" style="color: var(--text-secondary);">Pengaturan sistem aplikasi WASPRO</p>
                    </div>
                    <form action="{{ route('application-settings.clear-cache') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cache settings?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-lg text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @isset($categoryRoutes['workflow'])
            <a href="{{ $categoryRoutes['workflow'] }}" class="block p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Workflow Settings</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Persetujuan & alur kerja</p>
                    </div>
                </div>
            </a>
            @endisset

            @isset($categoryRoutes['upload'])
            <a href="{{ $categoryRoutes['upload'] }}" class="block p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Upload Settings</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Batasan file & dokumen</p>
                    </div>
                </div>
            </a>
            @endisset

            @isset($categoryRoutes['report'])
            <a href="{{ $categoryRoutes['report'] }}" class="block p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Report Settings</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Laporan & penjadwalan</p>
                    </div>
                </div>
            </a>
            @endisset

             @isset($categoryRoutes['expiry'])
             <a href="{{ $categoryRoutes['expiry'] }}" class="block p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                 <div class="flex items-center">
                     <div class="p-3 bg-orange-100 rounded-lg">
                         <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0l-3 3m0 6l-3 3m6 0m6 0v6"></path>
                         </svg>
                     </div>
                     <div class="ml-4">
                         <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Expiry Settings</h3>
                         <p class="text-sm" style="color: var(--text-secondary);">Kadaluarsa & notifikasi</p>
                     </div>
                 </div>
             </a>
             @endisset

              @isset($categoryRoutes['audit'])
              <a href="{{ $categoryRoutes['audit'] }}" class="block p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                  <div class="flex items-center">
                      <div class="p-3 bg-red-100 rounded-lg">
                          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Audit Trail</h3>
                          <p class="text-sm" style="color: var(--text-secondary);">Lihat semua jejak aktivitas sistem</p>
                      </div>
                  </div>
              </a>
               @endisset
        </div>

        <!-- Settings Overview Table -->
        @if($settingsByCategory->count() > 0)
            <div class="rounded-lg shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <!-- Tab Navigation -->
                <div class="border-b" style="border-color: var(--border-primary);">
                    <nav class="-mb-px flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
                        @foreach($settingsByCategory as $category => $settings)
                            <button class="tab-button {{ $loop->first ? 'border-blue-500 text-blue-600' : 'border-transparent hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200" 
                                    data-tab="{{ $category }}"
                                    type="button"
                                    style="{{ !$loop->first ? 'color: var(--text-secondary);' : '' }}">
                                <div class="flex items-center">
                                    @if($category === 'security')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    @elseif($category === 'system')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    @endif
                                    {{ ucfirst($category) }}
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: var(--secondary-bg-light); color: var(--text-secondary);">
                                        {{ $settings->count() }}
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    @foreach($settingsByCategory as $category => $settings)
                        <div class="tab-content {{ $loop->first ? '' : 'hidden' }}" id="{{ $category }}-content">
                            <div class="overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y" style="border-color: var(--border-primary);">
                                        <thead style="background-color: var(--border-primary);">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Key</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Value</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Type</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Description</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                                            @foreach($settings as $setting)
                                                <tr class="transition-colors duration-150" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <code class="px-2 py-1 text-sm font-mono rounded" style="background-color: var(--accent-bg); color: var(--accent-primary);">{{ $setting->key }}</code>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="max-w-xs truncate" title="{{ $setting->value }}">
                                                            @if($setting->type === 'boolean')
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $setting->value ? 'var(--success-bg)' : 'var(--danger-bg)' }}; color: {{ $setting->value ? 'var(--success-primary)' : 'var(--danger-primary)' }};">
                                                                    @if($setting->value)
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        True
                                                                    @else
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        False
                                                                    @endif
                                                                </span>
                                                            @elseif($setting->type === 'json')
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: var(--secondary-bg-light); color: var(--text-secondary);">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    JSON Object
                                                                </span>
                                                            @else
                                                                <span class="text-sm" style="color: var(--text-primary);">{{ $setting->value }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                                            {{ $setting->type }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="max-w-xs truncate text-sm" style="color: var(--text-secondary);" title="{{ $setting->description }}">
                                                            {{ $setting->description ?: '-' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('application-settings.show', $setting) }}" 
                                                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                                               title="Lihat Detail">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('application-settings.edit', $setting) }}" 
                                                               class="inline-flex items-center px-3 py-1.5 border border-yellow-300 rounded-md text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200"
                                                               title="Edit">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada settings ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-500">Jalankan database seeder untuk menambahkan settings.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active classes from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active classes to clicked tab
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            const targetContent = document.getElementById(targetTab + '-content');
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
});
</script>
@endsection