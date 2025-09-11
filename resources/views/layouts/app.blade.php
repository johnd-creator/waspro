<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 0.35rem;
            margin: 0.25rem 1rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1rem;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        .text-primary {
            color: #4e73df !important;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1050;
                transition: left 0.3s;
            }
            .sidebar.show {
            left: 0;
        }
    }
    
    /* User Dropdown Styles */
    .dropdown-toggle::after {
        margin-left: 0.5rem;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .dropdown-item:focus {
        background-color: #e9ecef;
    }
    
    /* Accordion Menu Styles */
    .nav-link[data-bs-toggle="collapse"] {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-link[data-bs-toggle="collapse"]:after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 1rem;
        transition: transform 0.3s ease;
    }
    
    .nav-link[data-bs-toggle="collapse"]:not(.collapsed):after {
        transform: rotate(180deg);
    }
    
    .nav-link[data-bs-toggle="collapse"].collapsed:after {
        transform: rotate(0deg);
    }
    
    .sb-sidenav-menu-nested {
        padding-left: 1rem;
    }
    
    .sb-sidenav-menu-nested .nav-link {
        padding: 0.75rem 1rem;
        margin: 0.125rem 1rem;
        font-size: 0.9rem;
        background-color: rgba(255, 255, 255, 0.05);
        border-left: 3px solid transparent;
    }
    
    .sb-sidenav-menu-nested .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-left-color: rgba(255, 255, 255, 0.5);
    }
    
    .sb-sidenav-menu-nested .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left-color: #fff;
    }
    
    .collapse {
        transition: height 0.35s ease;
    }
    
    .collapsing {
        transition: height 0.35s ease;
    }
    
    /* Ensure smooth collapse animation */
    .collapse:not(.show) {
        display: none;
    }
    
    .collapse.show {
        display: block;
    }
    </style>
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @auth
        <!-- Sidebar -->
        <nav class="sidebar position-fixed top-0 start-0" style="width: 250px; z-index: 1000;">
            <div class="p-4 text-center">
                <a class="navbar-brand text-white fw-bold d-block" href="{{ route('dashboard') }}" style="text-decoration: none; font-size: 1.5rem;">
                    <i class="fas fa-recycle me-2" style="font-size: 1.8rem;"></i>
                    WASPRO
                </a>
            </div>
            <hr class="text-white mx-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('log-penyimpanan.*') ? 'active' : '' }}" href="{{ route('log-penyimpanan.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        Log Penyimpanan Limbah
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }} {{ request()->routeIs('reports.*') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}" aria-controls="collapseReports">
                        <i class="fas fa-chart-bar"></i>
                        LAPORAN
                    </a>
                    <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="collapseReports">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-home"></i>
                                Dashboard Report
                            </a>
                            <a class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}" href="{{ route('reports.monthly') }}">
                                <i class="fas fa-calendar-alt"></i>
                                Laporan Bulanan
                            </a>
                            <a class="nav-link {{ request()->routeIs('reports.status') ? 'active' : '' }}" href="{{ route('reports.status') }}">
                                <i class="fas fa-info-circle"></i>
                                Laporan Status
                            </a>
                            <a class="nav-link {{ request()->routeIs('reports.waste-type') ? 'active' : '' }}" href="{{ route('reports.waste-type') }}">
                                <i class="fas fa-recycle"></i>
                                Laporan Jenis Limbah
                            </a>
                            <a class="nav-link {{ request()->routeIs('reports.company') ? 'active' : '' }}" href="{{ route('reports.company') }}">
                                <i class="fas fa-building"></i>
                                Laporan Perusahaan
                            </a>
                            <a class="nav-link {{ request()->routeIs('reports.unit') ? 'active' : '' }}" href="{{ route('reports.unit') }}">
                                <i class="fas fa-industry"></i>
                                Laporan Unit
                            </a>
                        </nav>
                    </div>
                </li>
                <li class="nav-item mt-3">
                    <div class="text-white-50 small px-3 mb-2">MASTER DATA</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('jenis-limbah.*', 'karakteristik-limbah.*', 'kategori-kegiatan-sumber.*') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLimbah" aria-expanded="{{ request()->routeIs('jenis-limbah.*', 'karakteristik-limbah.*', 'kategori-kegiatan-sumber.*') ? 'true' : 'false' }}" aria-controls="collapseLimbah">
                        <i class="fas fa-recycle"></i>
                        LIMBAH
                    </a>
                    <div class="collapse {{ request()->routeIs('jenis-limbah.*', 'karakteristik-limbah.*', 'kategori-kegiatan-sumber.*') ? 'show' : '' }}" id="collapseLimbah">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ request()->routeIs('jenis-limbah.*') ? 'active' : '' }}" href="{{ route('jenis-limbah.index') }}">
                                <i class="fas fa-file-alt"></i>
                                Jenis Limbah
                            </a>
                            <a class="nav-link {{ request()->routeIs('karakteristik-limbah.*') ? 'active' : '' }}" href="{{ route('karakteristik-limbah.index') }}">
                                <i class="fas fa-tags"></i>
                                Karakteristik Limbah
                            </a>
                            <a class="nav-link {{ request()->routeIs('kategori-kegiatan-sumber.*') ? 'active' : '' }}" href="{{ route('kategori-kegiatan-sumber.index') }}">
                                <i class="fas fa-list"></i>
                                Kategori Kegiatan Limbah
                            </a>
                        </nav>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perusahaan-penghasil.*') ? 'active' : '' }}" href="{{ route('perusahaan-penghasil.index') }}">
                        <i class="fas fa-building"></i>
                        Perusahaan Penghasil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('unit-pembangkit.*') ? 'active' : '' }}" href="{{ route('unit-pembangkit.index') }}">
                        <i class="fas fa-map-marker-alt"></i>
                        Unit Pembangkit
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengguna-sistem.*') ? 'active' : '' }}" href="{{ route('pengguna-sistem.index') }}">
                        <i class="fas fa-users"></i>
                        Pengelolaan Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('peran-pengguna.*') ? 'active' : '' }}" href="{{ route('peran-pengguna.index') }}">
                        <i class="fas fa-user-tag"></i>
                        Peran Pengguna
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <div class="text-white-50 small px-3 mb-2">SISTEM</div>
                </li>
                @if(Auth::guard('web')->user() && Auth::guard('web')->user()->isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('application-settings.*') ? 'active' : '' }}" href="{{ route('application-settings.index') }}">
                        <i class="fas fa-cogs"></i>
                        System Settings
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="d-none d-md-block text-start">
                                        <div class="fw-semibold text-dark">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                                        <div class="small text-muted">{{ Auth::user()->unitPembangkit->nama_unit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown" style="min-width: 280px;">
                                <li class="px-3 py-3 border-bottom bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-size: 18px;">
                                            {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                                            <div class="small text-muted">{{ Auth::user()->email_address ?? Auth::user()->email }}</div>
                                            <div class="small text-primary">{{ Auth::user()->unitPembangkit->nama_unit ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </li>
                                <li><a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2 text-primary"></i>Lihat Profile
                                </a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('pengguna-sistem.index') }}">
                                    <i class="fas fa-users me-2 text-success"></i>Kelola Users
                                </a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0" id="logoutForm" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="dropdown-item py-2 text-danger" onclick="handleLogout(event); return false;">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-4">
                @if(session('success'))
                    <div class="container-fluid">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="container-fluid">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
        @else
        <!-- Guest Layout -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        @endauth
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // Initialize Bootstrap dropdowns and collapses
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // Let Bootstrap handle collapse automatically - no custom initialization needed
            
            // Manual click handler for user dropdown
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                userDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dropdown = bootstrap.Dropdown.getInstance(userDropdown) || new bootstrap.Dropdown(userDropdown);
                    dropdown.toggle();
                });
            }
            
            // Safari-compatible logout handler
             window.handleLogout = function(e) {
                 if (e) {
                     e.preventDefault();
                     e.stopPropagation();
                 }
                 
                 // Use setTimeout to ensure Safari processes the event properly
                 setTimeout(function() {
                     if (confirm('Apakah Anda yakin ingin logout?')) {
                         var form = document.getElementById('logoutForm');
                         if (form) {
                             form.submit();
                         }
                     }
                 }, 10);
                 
                 return false;
             };
             
             // Global delete confirmation handler
             window.handleDeleteConfirm = function(e, message) {
                 e.preventDefault();
                 e.stopPropagation();
                 if (confirm(message || 'Apakah Anda yakin ingin menghapus data ini?')) {
                     e.target.closest('form').submit();
                 }
                 return false;
             };
             
             // Global form submission confirmation handler
             window.handleFormConfirm = function(form, message) {
                 if (confirm(message || 'Apakah Anda yakin?')) {
                     form.submit();
                 }
                 return false;
             };
        });
    </script>
    
    @stack('scripts')
</body>
</html>
