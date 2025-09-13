@extends('layouts.app')

@section('title', 'Notifikasi Sistem')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notifikasi Sistem</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifikasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Notification Statistics -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="expired-count">0</h3>
                        <p>Limbah Kadaluarsa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="urgent-count">0</h3>
                        <p>Kritis (≤3 hari)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="critical-count">0</h3>
                        <p>Peringatan (≤7 hari)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="warning-count">0</h3>
                        <p>Perhatian (≤30 hari)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Daftar Notifikasi
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" onclick="refreshNotifications()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <div class="btn-group ml-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item filter-btn" href="#" data-filter="all">Semua</a>
                            <a class="dropdown-item filter-btn" href="#" data-filter="expired">Kadaluarsa</a>
                            <a class="dropdown-item filter-btn" href="#" data-filter="urgent">Kritis</a>
                            <a class="dropdown-item filter-btn" href="#" data-filter="critical">Peringatan</a>
                            <a class="dropdown-item filter-btn" href="#" data-filter="warning">Perhatian</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="notifications-container">
                    <!-- Notifications will be loaded here -->
                </div>
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
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Tidak ada notifikasi</h5>
                    <p class="text-muted">Semua limbah dalam kondisi baik</p>
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
                <div class="notification-item border-bottom p-3" data-type="${notification.type}">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="notification-icon bg-${notification.color} rounded-circle p-3">
                                <i class="${notification.icon} text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-1">
                                        <strong>${notification.title}</strong>
                                        <span class="badge badge-${notification.color} ml-2">${daysText}</span>
                                    </h6>
                                    <p class="mb-1">${notification.message}</p>
                                    <div class="text-muted small">
                                        <i class="fas fa-industry mr-1"></i>${notification.perusahaan} |
                                        <i class="fas fa-building ml-2 mr-1"></i>${notification.unit} |
                                        <i class="fas fa-weight ml-2 mr-1"></i>${notification.jumlah} kg
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="mb-2">
                                        <small class="text-muted">${timeAgo}</small>
                                    </div>
                                    <a href="{{ route('log-penyimpanan.show', '') }}/${notification.id}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye mr-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(item);
        });
    }
    
    // Filter button click handler
    $('.filter-btn').click(function(e) {
        e.preventDefault();
        currentFilter = $(this).data('filter');
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        displayNotifications(filterNotifications(allNotifications, currentFilter));
    });
    
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
.notification-item {
    transition: background-color 0.3s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-btn.active {
    background-color: #007bff;
    color: white;
}
</style>
@endpush