<!-- Notifications Dropdown -->
<div class="relative">
    <button class="relative p-2 focus:outline-none rounded-xl transition-all duration-200" id="notification-bell" onclick="toggleNotificationDropdown()" style="color: var(--text-secondary);">
        <i class="far fa-bell text-lg"></i>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden" id="notification-count">0</span>
    </button>
    
    <div class="absolute right-0 mt-3 w-96 rounded-2xl shadow-2xl border z-50 hidden overflow-hidden notification-dropdown" id="notification-dropdown" style="background: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-4 border-b" style="background: var(--card-secondary-bg); border-color: var(--border-primary);">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg" style="color: var(--text-primary);">Notifikasi</h3>
                    <p class="text-sm" id="notification-header" style="color: var(--text-secondary);">0 notifikasi baru</p>
                </div>
                <div style="color: var(--accent-primary);">
                    <i class="fas fa-bell text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="max-h-80 overflow-y-auto" id="notification-list">
            <!-- Notifications will be loaded here -->
        </div>
        
        <div class="border-t" style="border-color: var(--border-primary); background: var(--card-secondary-bg);">
            <a href="{{ route('notifications.index') }}" class="flex items-center justify-center w-full px-6 py-3 transition-all duration-200 font-medium" style="color: var(--accent-primary);">
                <i class="fas fa-external-link-alt mr-2"></i>
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
// Notification dropdown functionality
let isDropdownOpen = false;

function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notification-dropdown');
    isDropdownOpen = !isDropdownOpen;
    
    if (isDropdownOpen) {
        dropdown.classList.remove('hidden');
        loadNotificationList();
    } else {
        dropdown.classList.add('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notification-dropdown');
    const bell = document.getElementById('notification-bell');
    
    if (!dropdown.contains(event.target) && !bell.contains(event.target)) {
        dropdown.classList.add('hidden');
        isDropdownOpen = false;
    }
});

function loadNotifications() {
    fetch('/notifications/get-count')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            const headerElement = document.getElementById('notification-header');
            
            if (data.count > 0) {
                countElement.textContent = data.count;
                countElement.classList.remove('hidden');
                headerElement.textContent = `${data.count} notifikasi baru`;
            } else {
                countElement.classList.add('hidden');
                headerElement.textContent = '0 notifikasi baru';
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function loadNotificationList() {
    const listElement = document.getElementById('notification-list');
    listElement.innerHTML = '<div class="p-4 text-center" style="color: var(--text-secondary)"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat...</div>';
    
    // Simulate loading notifications (replace with actual API call)
    setTimeout(() => {
        listElement.innerHTML = `
            <div class="p-4 notification-list-item cursor-pointer transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--card-secondary-bg);">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text-primary);">Limbah Mendekati Expired</p>
                        <p class="text-sm" style="color: var(--text-secondary);">5 jenis limbah akan expired dalam 7 hari</p>
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">2 jam yang lalu</p>
                    </div>
                </div>
            </div>
            <div class="p-4 notification-list-item cursor-pointer transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--card-secondary-bg);">
                            <i class="fas fa-plus-circle text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text-primary);">Log Baru Ditambahkan</p>
                        <p class="text-sm" style="color: var(--text-secondary);">PT ABC menambahkan log limbah B3</p>
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">5 jam yang lalu</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-center" style="color: var(--text-secondary);">
                <p class="text-sm">Tidak ada notifikasi lainnya</p>
            </div>
        `;
    }, 500);
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});
</script>

<style>
/* Konsistensi tema dan kontras untuk dropdown notifikasi */
.notification-list-item {
    border-bottom: 1px solid var(--border-primary);
    transition: background-color 0.2s ease;
}

.notification-list-item:hover {
    background-color: var(--hover-bg);
}

.notification-list-item:last-child {
    border-bottom: none;
}

#notification-bell:hover {
    background-color: var(--hover-bg);
    color: var(--text-primary);
}

#notification-dropdown a:hover {
    background-color: var(--hover-bg);
}

#notification-dropdown {
    backdrop-filter: saturate(180%) blur(8px);
}

.media-object {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-menu-lg {
    min-width: 350px;
    max-height: 400px;
    overflow-y: auto;
}

@keyframes bellShake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    75% { transform: rotate(10deg); }
}

.notification-bell-animate {
    animation: bellShake 0.5s ease-in-out;
}
</style>