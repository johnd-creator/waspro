<!-- Notifications Dropdown -->
<div class="relative">
    <button class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none rounded-xl hover:bg-gray-50 transition-all duration-200" id="notification-bell" onclick="toggleNotificationDropdown()">
        <i class="far fa-bell text-lg"></i>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden" id="notification-count">0</span>
    </button>
    
    <div class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 hidden overflow-hidden" id="notification-dropdown">
        <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-red-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Notifikasi</h3>
                    <p class="text-sm text-gray-600" id="notification-header">0 notifikasi baru</p>
                </div>
                <div class="text-orange-500">
                    <i class="fas fa-bell text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="max-h-80 overflow-y-auto" id="notification-list">
            <!-- Notifications will be loaded here -->
        </div>
        
        <div class="border-t border-gray-100 bg-gray-50">
            <a href="{{ route('notifications.index') }}" class="flex items-center justify-center w-full px-6 py-3 text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 font-medium">
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
    listElement.innerHTML = '<div class="p-4 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat...</div>';
    
    // Simulate loading notifications (replace with actual API call)
    setTimeout(() => {
        listElement.innerHTML = `
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Limbah Mendekati Expired</p>
                        <p class="text-sm text-gray-600">5 jenis limbah akan expired dalam 7 hari</p>
                        <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                    </div>
                </div>
            </div>
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus-circle text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Log Baru Ditambahkan</p>
                        <p class="text-sm text-gray-600">PT ABC menambahkan log limbah B3</p>
                        <p class="text-xs text-gray-400 mt-1">5 jam yang lalu</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-center text-gray-500">
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
.notification-item {
    border-bottom: 1px solid #f4f4f4;
    transition: background-color 0.3s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
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