@extends('layouts.app')

@section('title', 'Notifikasi Sistem')

@section('content')
<!-- Modern Header Section -->
<div class="mb-6">
    <div class="container-fluid">
        <!-- Page Header Card -->
        <div style="background: var(--card-bg); border-color: var(--border-primary);" class="backdrop-blur-xl border rounded-2xl p-6 shadow-xl shadow-slate-900/5 transition-all duration-300 hover:shadow-2xl hover:shadow-slate-900/10" data-aos="fade-up">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center text-white mr-4 shadow-xl">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <div>
                        <h1 style="color: var(--text-primary);" class="text-3xl font-bold mb-1 tracking-tight">Notifikasi Sistem</h1>
                        <div class="flex items-center gap-2 text-sm">
                            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary);" class="hover:text-blue-600 transition-colors duration-200 font-medium">
                                <i class="fas fa-home mr-1"></i>
                                Dashboard
                            </a>
                            <i class="fas fa-chevron-right text-xs" style="color: var(--text-secondary);"></i>
                            <span style="color: var(--text-primary);" class="font-semibold">Notifikasi</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div style="color: var(--text-secondary);" class="text-sm font-medium">Status Sistem</div>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span style="color: var(--text-primary);" class="text-lg font-semibold">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Expired Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);" class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-red-500/10 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold" id="expired-count">0</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Kadaluarsa</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-red-600">
                    <i class="fas fa-exclamation-circle text-xs"></i>
                    <span class="text-xs font-semibold">Perlu Tindakan</span>
                </div>
            </div>

            <!-- Urgent Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);" class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-amber-500/10 hover:shadow-xl hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-clock text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold" id="urgent-count">0</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Mendesak</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-amber-600">
                    <i class="fas fa-clock text-xs"></i>
                    <span class="text-xs font-semibold">Segera Ditangani</span>
                </div>
            </div>

            <!-- Critical Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);" class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-blue-500/10 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-bell text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold" id="critical-count">0</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Kritis</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-blue-600">
                    <i class="fas fa-bell text-xs"></i>
                    <span class="text-xs font-semibold">Prioritas Tinggi</span>
                </div>
            </div>

            <!-- Warning Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);" class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-emerald-500/10 hover:shadow-xl hover:shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-info-circle text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold" id="warning-count">0</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Peringatan</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-emerald-600">
                    <i class="fas fa-info-circle text-xs"></i>
                    <span class="text-xs font-semibold">Informasi</span>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div style="background: var(--card-bg); border-color: var(--border-primary);" class="backdrop-blur-xl border rounded-2xl shadow-xl shadow-slate-900/5 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-slate-900/10" data-aos="fade-up" data-aos-delay="500">
            <!-- Card Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 p-8 pb-6 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-blue-700">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="p-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-xl border-2 border-yellow-300 hover:from-yellow-300 hover:to-orange-400 transition-all duration-300 hover:scale-105">
                            <i class="fas fa-bell text-2xl text-white drop-shadow-lg"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-white tracking-tight">Notifikasi Limbah</h3>
                    </div>
                    <p class="text-blue-100 text-lg leading-relaxed">Pantau status limbah yang memerlukan perhatian</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="text-right text-white">
                        <div class="text-sm text-blue-100 font-medium">Total Notifikasi</div>
                        <div class="text-2xl font-bold" id="total-count">0</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" class="inline-flex items-center gap-3 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 border-2 border-emerald-400 rounded-xl hover:from-emerald-600 hover:to-emerald-700 hover:border-emerald-300 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-emerald-300 focus:ring-offset-2 shadow-xl hover:shadow-2xl backdrop-blur-sm" onclick="refreshNotifications()">
                            <i class="fas fa-sync-alt text-base"></i>
                            <span class="font-bold">Refresh</span>
                        </button>
                        <div class="relative">
                            <button type="button" class="inline-flex items-center gap-3 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 border-2 border-blue-400 rounded-xl hover:from-blue-600 hover:to-blue-700 hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 shadow-xl hover:shadow-2xl backdrop-blur-sm" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter text-base"></i>
                                <span class="font-bold">Filter: <span id="currentFilter" class="font-black">Semua</span></span>
                                <i class="fas fa-chevron-down text-sm ml-1 transition-transform duration-200" id="filterChevron"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-2xl border-0 rounded-xl p-2 mt-2 overflow-hidden" style="background: var(--card-bg); border-color: var(--border-primary);" aria-labelledby="filterDropdown">
                                <li><a class="dropdown-item rounded-lg py-3 px-4 filter-option active d-flex align-items-center gap-3 font-medium hover:bg-blue-50 transition-colors duration-200" href="#" data-filter="all" style="color: var(--text-primary);"><i class="fas fa-list text-blue-600"></i><span>Semua Notifikasi</span></a></li>
                                <li><hr class="dropdown-divider my-2" style="border-color: var(--border-primary);"></li>
                                <li><a class="dropdown-item rounded-lg py-3 px-4 filter-option d-flex align-items-center gap-3 font-medium hover:bg-red-50 transition-colors duration-200" href="#" data-filter="expired" style="color: var(--text-primary);"><i class="fas fa-exclamation-triangle text-red-600"></i><span>Kadaluarsa</span></a></li>
                                <li><a class="dropdown-item rounded-lg py-3 px-4 filter-option d-flex align-items-center gap-3 font-medium hover:bg-amber-50 transition-colors duration-200" href="#" data-filter="urgent" style="color: var(--text-primary);"><i class="fas fa-clock text-amber-600"></i><span>Mendesak</span></a></li>
                                <li><a class="dropdown-item rounded-lg py-3 px-4 filter-option d-flex align-items-center gap-3 font-medium hover:bg-blue-50 transition-colors duration-200" href="#" data-filter="critical" style="color: var(--text-primary);"><i class="fas fa-bell text-blue-600"></i><span>Kritis</span></a></li>
                                <li><a class="dropdown-item rounded-lg py-3 px-4 filter-option d-flex align-items-center gap-3 font-medium hover:bg-emerald-50 transition-colors duration-200" href="#" data-filter="warning" style="color: var(--text-primary);"><i class="fas fa-info-circle text-emerald-600"></i><span>Peringatan</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="p-0">
                <!-- Empty State -->
                <div class="text-center py-12 px-6" id="empty-state" style="display: none;">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full mb-4">
                            <i class="fas fa-bell-slash text-3xl text-slate-400"></i>
                        </div>
                    </div>
                    <div class="max-w-sm mx-auto">
                        <h3 style="color: var(--text-primary);" class="text-xl font-bold mb-2">Tidak Ada Notifikasi</h3>
                        <p style="color: var(--text-secondary);" class="text-sm leading-relaxed mb-6">Saat ini tidak ada limbah yang memerlukan perhatian khusus. Sistem akan memberitahu Anda jika ada limbah yang mendekati masa kadaluarsa.</p>
                        <button type="button" class="inline-flex items-center gap-3 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 border-2 border-emerald-400 rounded-xl hover:from-emerald-600 hover:to-emerald-700 hover:border-emerald-300 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-emerald-300 focus:ring-offset-2 shadow-xl hover:shadow-2xl backdrop-blur-sm" onclick="refreshNotifications()">
                            <i class="fas fa-sync-alt text-base"></i>
                            <span class="font-bold">Periksa Ulang</span>
                        </button>
                    </div>
                </div>
                
                <!-- Notifications Container -->
                <div id="notifications-container" class="p-6 space-y-4"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFilter = 'all';
    let allNotifications = [];
    
    // Load notifications on page load
    loadNotifications();
    
    // Auto refresh every 5 minutes
    setInterval(loadNotifications, 300000);
    
    // Load notifications function
    function loadNotifications() {
        $.get('{{ route("notifications.index") }}', function(data) {
            allNotifications = data.notifications;
            updateStatistics(data.notifications);
            displayNotifications(filterNotifications(data.notifications, currentFilter));
        }).fail(function() {
            showError('Gagal memuat notifikasi');
        });
    }
    
    // Update statistics
    function updateStatistics(notifications) {
        const stats = {
            expired: 0,
            urgent: 0,
            critical: 0,
            warning: 0
        };
        
        notifications.forEach(function(notification) {
            if (stats.hasOwnProperty(notification.type)) {
                stats[notification.type]++;
            }
        });
        
        $('#expired-count').text(stats.expired);
        $('#urgent-count').text(stats.urgent);
        $('#critical-count').text(stats.critical);
        $('#warning-count').text(stats.warning);
        
        // Update total count
        updateTotalCount(notifications);
    }
    
    // Filter notifications
    function filterNotifications(notifications, filter) {
        if (filter === 'all') {
            return notifications;
        }
        return notifications.filter(function(notification) {
            return notification.type === filter;
        });
    }
    
    // Display notifications
    function displayNotifications(notifications) {
        const $container = $('#notifications-container');
        $container.empty();
        
        if (notifications.length === 0) {
            $container.append(`
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="empty-state-content">
                        <h4 class="empty-state-title">Tidak ada notifikasi</h4>
                        <p class="empty-state-text text-muted mb-4">Semua limbah dalam kondisi baik dan tidak memerlukan perhatian khusus</p>
                        <button class="btn btn-outline-primary" onclick="refreshNotifications()">
                            <i class="fas fa-sync-alt mr-2"></i>Periksa Ulang
                        </button>
                    </div>
                </div>
            `);
            return;
        }
        
        notifications.forEach(function(notification) {
            const timeAgo = moment(notification.created_at).fromNow();
            const daysText = notification.days_until_expiry >= 0 
                ? notification.days_until_expiry + ' hari lagi' 
                : 'Kadaluarsa ' + Math.abs(notification.days_until_expiry) + ' hari';
            
            const item = `
                <div class="notification-item" data-type="${notification.type}" data-aos="fade-up">
                    <div style="background: var(--card-bg); border-color: var(--border-primary);" class="group backdrop-blur-xl border rounded-xl p-6 shadow-lg shadow-${notification.color}-500/10 hover:shadow-xl hover:shadow-${notification.color}-500/20 transition-all duration-300 hover:-translate-y-1 border-l-4 border-l-${notification.color}-500">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Main Content -->
                            <div class="flex-1">
                                <div class="flex items-start gap-4 mb-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="p-3 bg-gradient-to-br from-${notification.color}-500 to-${notification.color}-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                                            <i class="${notification.icon} text-lg text-white"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <h4 style="color: var(--text-primary);" class="text-lg font-bold mb-2 group-hover:text-${notification.color}-600 transition-colors duration-200">
                                            ${notification.title}
                                        </h4>
                                        
                                        <!-- Meta Information -->
                                        <div class="flex flex-wrap items-center gap-4 mb-3 text-sm">
                                            <div class="flex items-center gap-1" style="color: var(--text-secondary);">
                                                <i class="fas fa-clock text-xs"></i>
                                                <span>${timeAgo}</span>
                                            </div>
                                            <div class="flex items-center gap-1" style="color: var(--text-secondary);">
                                                <i class="fas fa-calendar text-xs"></i>
                                                <span>${daysText}</span>
                                            </div>
                                            <div class="flex items-center gap-1" style="color: var(--text-secondary);">
                                                <i class="fas fa-building text-xs"></i>
                                                <span>${notification.perusahaan}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Message -->
                                        <p style="color: var(--text-secondary);" class="mb-3 leading-relaxed">${notification.message}</p>
                                        
                                        <!-- Details Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div style="background: var(--card-secondary-bg);" class="p-3 rounded-lg">
                                                <div style="color: var(--text-secondary);" class="text-xs font-medium uppercase tracking-wider mb-1">Unit</div>
                                                <div style="color: var(--text-primary);" class="font-semibold">${notification.unit}</div>
                                            </div>
                                            <div style="background: var(--card-secondary-bg);" class="p-3 rounded-lg">
                                                <div style="color: var(--text-secondary);" class="text-xs font-medium uppercase tracking-wider mb-1">Jumlah</div>
                                                <div style="color: var(--text-primary);" class="font-semibold">${notification.jumlah} kg</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col items-end gap-3 lg:ml-6">
                                <!-- Action Button -->
                                <a href="{{ url('log-penyimpanan') }}/${notification.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-${notification.color}-600 bg-${notification.color}-50 border border-${notification.color}-200 rounded-lg hover:bg-${notification.color}-100 hover:border-${notification.color}-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-${notification.color}-500 focus:ring-offset-2">
                                    <i class="fas fa-eye text-sm"></i>
                                    <span>Lihat Detail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(item);
        });
    }
    
    // Filter button click handler
    $('.filter-option').click(function(e) {
        e.preventDefault();
        
        // Remove active class from all options
        $('.filter-option').removeClass('active');
        
        // Add active class to clicked option
        $(this).addClass('active');
        
        // Update current filter text
        const filterText = $(this).text().trim();
        $('#currentFilter').text(filterText);
        
        // Get filter value
        currentFilter = $(this).data('filter');
        
        // Filter notifications
        displayNotifications(filterNotifications(allNotifications, currentFilter));
    });
    
    // Dropdown chevron animation
    $('#filterDropdown').on('click', function() {
        const $chevron = $('#filterChevron');
        const isExpanded = $(this).attr('aria-expanded') === 'true';
        
        if (isExpanded) {
            $chevron.css('transform', 'rotate(180deg)');
        } else {
            $chevron.css('transform', 'rotate(0deg)');
        }
    });
    
    // Reset chevron when dropdown is hidden
    $(document).on('click', function(e) {
        if (!$('#filterDropdown').is(e.target) && $('#filterDropdown').has(e.target).length === 0) {
            $('#filterChevron').css('transform', 'rotate(0deg)');
        }
    });
    
    // Update total count
    function updateTotalCount(notifications) {
        $('#total-count').text(notifications.length);
    }
    
    // Refresh function
    window.refreshNotifications = function() {
        const $btn = $('button[onclick="refreshNotifications()"]');
        const $icon = $btn.find('i');
        
        $icon.addClass('fa-spin');
        $btn.prop('disabled', true);
        
        loadNotifications();
        
        setTimeout(function() {
            $icon.removeClass('fa-spin');
            $btn.prop('disabled', false);
        }, 1000);
    };
    
    // Show error message
    function showError(message) {
        toastr.error(message);
    }
});
</script>
@endpush

@push('styles')
<style>
/* CSS Variables for Theme Support */
:root {
    --card-bg: rgba(255, 255, 255, 0.95);
    --card-secondary-bg: rgba(248, 250, 252, 0.8);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-primary: rgba(226, 232, 240, 0.8);
    --hover-bg: rgba(248, 250, 252, 0.5);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    :root {
        --card-bg: rgba(30, 41, 59, 0.95);
        --card-secondary-bg: rgba(51, 65, 85, 0.8);
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border-primary: rgba(71, 85, 105, 0.8);
        --hover-bg: rgba(51, 65, 85, 0.5);
    }
}

/* Global Styles */
.backdrop-blur-xl {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

/* Grid System */
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 0.75rem; }
.gap-4 { gap: 1rem; }
.gap-6 { gap: 1.5rem; }

/* Flexbox Utilities */
.flex { display: flex; }
.flex-col { flex-direction: column; }
.flex-wrap { flex-wrap: wrap; }
.items-center { align-items: center; }
.items-start { align-items: flex-start; }
.items-end { align-items: flex-end; }
.justify-between { justify-content: space-between; }
.justify-center { justify-content: center; }
.flex-1 { flex: 1 1 0%; }
.flex-shrink-0 { flex-shrink: 0; }

/* Spacing */
.p-0 { padding: 0; }
.p-2 { padding: 0.5rem; }
.p-3 { padding: 0.75rem; }
.p-4 { padding: 1rem; }
.p-6 { padding: 1.5rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
.py-12 { padding-top: 3rem; padding-bottom: 3rem; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
.px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
.mb-1 { margin-bottom: 0.25rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-3 { margin-bottom: 0.75rem; }
.mb-4 { margin-bottom: 1rem; }
.mb-6 { margin-bottom: 1.5rem; }
.ml-1 { margin-left: 0.25rem; }

/* Border Radius */
.rounded-lg { border-radius: 0.5rem; }
.rounded-xl { border-radius: 0.75rem; }
.rounded-2xl { border-radius: 1rem; }
.rounded-full { border-radius: 9999px; }

/* Text Utilities */
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-sm { font-size: 0.875rem; line-height: 1.25rem; }
.text-lg { font-size: 1.125rem; line-height: 1.75rem; }
.text-xl { font-size: 1.25rem; line-height: 1.75rem; }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
.uppercase { text-transform: uppercase; }
.tracking-wider { letter-spacing: 0.05em; }
.leading-relaxed { line-height: 1.625; }
.text-center { text-align: center; }
.text-right { text-align: right; }

/* Width & Height */
.w-20 { width: 5rem; }
.h-20 { height: 5rem; }
.min-w-0 { min-width: 0px; }
.max-w-sm { max-width: 24rem; }

/* Position */
.relative { position: relative; }
.inline-flex { display: inline-flex; }

/* Overflow */
.overflow-hidden { overflow: hidden; }

/* Transitions */
.transition-all { transition-property: all; }
.transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; }
.transition-transform { transition-property: transform; }
.duration-200 { transition-duration: 200ms; }
.duration-300 { transition-duration: 300ms; }

/* Transforms */
.hover\:-translate-y-1:hover { transform: translateY(-0.25rem); }
.group:hover .group-hover\:scale-105 { transform: scale(1.05); }
.group:hover .group-hover\:text-red-600 { color: #dc2626; }
.group:hover .group-hover\:text-amber-600 { color: #d97706; }
.group:hover .group-hover\:text-blue-600 { color: #2563eb; }
.group:hover .group-hover\:text-emerald-600 { color: #059669; }

/* Shadows */
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
.shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
.shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
.shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }

/* Color-specific shadows */
.shadow-red-500\/10 { box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.1), 0 4px 6px -2px rgba(239, 68, 68, 0.05); }
.shadow-amber-500\/10 { box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.1), 0 4px 6px -2px rgba(245, 158, 11, 0.05); }
.shadow-blue-500\/10 { box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -2px rgba(59, 130, 246, 0.05); }
.shadow-emerald-500\/10 { box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05); }
.shadow-slate-900\/5 { box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.05), 0 4px 6px -2px rgba(15, 23, 42, 0.025); }

.hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
.hover\:shadow-2xl:hover { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
.hover\:shadow-red-500\/20:hover { box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.2), 0 10px 10px -5px rgba(239, 68, 68, 0.1); }
.hover\:shadow-amber-500\/20:hover { box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.2), 0 10px 10px -5px rgba(245, 158, 11, 0.1); }
.hover\:shadow-blue-500\/20:hover { box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.2), 0 10px 10px -5px rgba(59, 130, 246, 0.1); }
.hover\:shadow-emerald-500\/20:hover { box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.2), 0 10px 10px -5px rgba(16, 185, 129, 0.1); }
.hover\:shadow-slate-900\/10:hover { box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 10px 10px -5px rgba(15, 23, 42, 0.05); }

/* Borders */
.border { border-width: 1px; }
.border-b { border-bottom-width: 1px; }
.border-l-4 { border-left-width: 4px; }
.border-l-red-500 { border-left-color: #ef4444; }
.border-l-amber-500 { border-left-color: #f59e0b; }
.border-l-blue-500 { border-left-color: #3b82f6; }
.border-l-emerald-500 { border-left-color: #10b981; }

/* Background Colors */
.bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
.bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
.from-red-500 { --tw-gradient-from: #ef4444; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(239, 68, 68, 0)); }
.to-red-600 { --tw-gradient-to: #dc2626; }
.from-amber-500 { --tw-gradient-from: #f59e0b; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(245, 158, 11, 0)); }
.to-amber-600 { --tw-gradient-to: #d97706; }
.from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
.to-blue-600 { --tw-gradient-to: #2563eb; }
.from-blue-600 { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
.to-purple-600 { --tw-gradient-to: #9333ea; }
.from-emerald-500 { --tw-gradient-from: #10b981; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(16, 185, 129, 0)); }
.to-emerald-600 { --tw-gradient-to: #059669; }
.from-slate-100 { --tw-gradient-from: #f1f5f9; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(241, 245, 249, 0)); }
.to-slate-200 { --tw-gradient-to: #e2e8f0; }

.bg-white { background-color: #ffffff; }
.bg-opacity-20 { --tw-bg-opacity: 0.2; }
.bg-opacity-30 { --tw-bg-opacity: 0.3; }

/* Text Colors */
.text-white { color: #ffffff; }
.text-blue-100 { color: #dbeafe; }
.text-blue-600 { color: #2563eb; }
.text-red-600 { color: #dc2626; }
.text-amber-600 { color: #d97706; }
.text-emerald-600 { color: #059669; }
.text-slate-400 { color: #94a3b8; }

/* Responsive Design */
@media (min-width: 640px) {
    .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .sm\:flex-row { flex-direction: row; }
    .sm\:items-center { align-items: center; }
    .sm\:justify-between { justify-content: space-between; }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .lg\:flex-row { flex-direction: row; }
    .lg\:items-center { align-items: center; }
    .lg\:justify-between { justify-content: space-between; }
    .lg\:ml-6 { margin-left: 1.5rem; }
}

/* Focus States */
.focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; }
.focus\:ring-2:focus { --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color); --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color); box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000); }
.focus\:ring-offset-2:focus { --tw-ring-offset-width: 2px; }

/* Hover States */
.hover\:bg-blue-50:hover { background-color: #eff6ff; }
.hover\:bg-white:hover { background-color: #ffffff; }
.hover\:from-blue-600:hover { --tw-gradient-from: #2563eb; }
.hover\:to-purple-700:hover { --tw-gradient-to: #7c3aed; }

/* Space Between */
.space-y-4 > :not([hidden]) ~ :not([hidden]) { --tw-space-y-reverse: 0; margin-top: calc(1rem * calc(1 - var(--tw-space-y-reverse))); margin-bottom: calc(1rem * var(--tw-space-y-reverse)); }

/* Custom Dropdown Styling */
.dropdown-menu {
    min-width: 200px;
}

.dropdown-item:hover {
    background-color: var(--hover-bg) !important;
}

.dropdown-item.active {
    background: linear-gradient(135deg, #3b82f6 0%, #9333ea 100%) !important;
    color: white !important;
}

/* Animation Support */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

[data-aos="fade-up"] {
    animation: fadeInUp 0.6s ease-out;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Additional Utility Classes */
.tracking-tight {
    letter-spacing: -0.025em;
}

.w-2 { width: 0.5rem; }
.h-2 { height: 0.5rem; }
.w-16 { width: 4rem; }
.h-16 { height: 4rem; }

.mr-1 { margin-right: 0.25rem; }
.mr-4 { margin-right: 1rem; }
.mt-1 { margin-top: 0.25rem; }

.gap-2 { gap: 0.5rem; }

/* Color Classes */
.bg-emerald-500 { background-color: #10b981; }
.hover\:text-blue-600:hover { color: #2563eb; }
</style>
@endpush