<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS will be loaded via Vite -->
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Styles -->
    <style>
        /* CSS Variables for Dark Mode */
        :root {
            /* Light theme colors */
            --bg-primary: #f9fafb;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f3f4f6;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --border-primary: #e5e7eb;
            --border-secondary: #d1d5db;
            --shadow-primary: rgba(0, 0, 0, 0.1);
            --shadow-secondary: rgba(0, 0, 0, 0.05);
            --input-bg: #ffffff;
            --input-text: #374151;
            --input-placeholder: #9ca3af;
            --navbar-bg: rgba(255, 255, 255, 0.95);
            --sidebar-bg: linear-gradient(to bottom right, #0f172a, #1e3a8a, #0f172a);
            --sidebar-bg-start: #0f172a;
            --sidebar-bg-via: #1e3a8a;
            --sidebar-bg-end: #0f172a;
            --card-bg: #ffffff;
            --card-secondary-bg: #f8fafc;
            --hover-bg: #f3f4f6;
            --gradient-start: #dbeafe;
            --gradient-end: #e0e7ff;
            --accent-primary: #2563eb;
            --accent-secondary: #059669;
            --accent-bg: #dbeafe;
            --accent-bg-secondary: #d1fae5;
            --danger-primary: #dc2626;
            --danger-bg: #fef2f2;
            --danger-bg-light: #fee2e2;
            --danger-hover: #b91c1c;
        }
        
        /* Dark theme colors */
        [data-theme="dark"] {
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-tertiary: #9ca3af;
            --border-primary: #374151;
            --border-secondary: #4b5563;
            --shadow-primary: rgba(0, 0, 0, 0.3);
            --shadow-secondary: rgba(0, 0, 0, 0.2);
            --input-bg: #374151;
            --input-text: #f9fafb;
            --input-placeholder: #9ca3af;
            --navbar-bg: rgba(31, 41, 55, 0.95);
            --sidebar-bg: linear-gradient(to bottom right, #0f172a, #1e3a8a, #0f172a);
            --sidebar-bg-start: #030712;
            --sidebar-bg-via: #1e40af;
            --sidebar-bg-end: #030712;
            --card-bg: #1f2937;
            --card-secondary-bg: #374151;
            --hover-bg: #374151;
            --gradient-start: #374151;
            --gradient-end: #4b5563;
            --accent-primary: #3b82f6;
            --accent-secondary: #10b981;
            --accent-bg: #1e3a8a;
            --accent-bg-secondary: #065f46;
            --danger-primary: #ef4444;
            --danger-bg: #450a0a;
            --danger-bg-light: #7f1d1d;
            --danger-hover: #dc2626;
        }
        
        /* Apply theme variables */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Custom styles */
        
        /* Safari input text color fix */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        input[type="search"],
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        textarea,
        select {
            color: #374151 !important;
            -webkit-text-fill-color: #374151 !important;
            opacity: 1 !important;
            -webkit-opacity: 1 !important;
        }
        
        /* Safari autofill background fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #374151 !important;
        }
        
        /* Placeholder text color for Safari */
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        
        /* Safari compatibility fixes for dashboard header */
        .safari-gradient-fallback {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 50%, #f3e8ff 100%);
        }
        
        /* Safari backdrop-blur fallback */
        @supports not (backdrop-filter: blur(10px)) {
            .bg-white\/80 {
                background-color: rgba(255, 255, 255, 0.95) !important;
            }
        }
        
        /* Safari transform and animation fixes */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .animate-pulse {
                animation: safari-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            @keyframes safari-pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: .5;
                }
            }
        }
        
        /* Ensure proper rendering on Safari */
        .dashboard-header {
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

    </style>
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: var(--bg-primary);">
    <div class="flex h-screen">
        @auth
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shadow-2xl" id="sidebar" style="background: linear-gradient(to bottom right, var(--sidebar-bg-start), var(--sidebar-bg-via), var(--sidebar-bg-end));">
            <nav class="h-full flex flex-col">
                <div class="flex items-center justify-center h-16 px-4 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-recycle text-white text-lg"></i>
                        </div>
                        <span class="text-white text-xl font-bold tracking-wide">WASPRO</span>
                    </div>
                </div>
                
                <div class="flex-1 px-2 py-3 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-transparent">
                    <div class="space-y-3">
                        <!-- Menu Dashboard -->
                        <div class="space-y-1">
                            <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('dashboard') ? 'bg-blue-600/80 text-white shadow-lg shadow-blue-600/25' : '' }}" href="{{ route('dashboard') }}">
                                <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-blue-500/50 transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt text-xs"></i>
                                </div>
                                <span class="text-sm font-medium">Dashboard</span>
                            </a>
                        </div>
                        
                        <!-- Menu Notifikasi -->
                        <div class="space-y-1">
                            <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('notifications.*') ? 'bg-purple-600/80 text-white shadow-lg shadow-purple-600/25' : '' }}" href="{{ route('notifications.index') }}">
                                <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-purple-500/50 transition-colors duration-200 relative">
                                    <i class="fas fa-bell text-xs"></i>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full text-xs flex items-center justify-center" id="sidebar-notification-badge" style="display: none;"></span>
                                </div>
                                <span class="text-sm font-medium">Notifikasi</span>
                            </a>
                        </div>
                        
                        <!-- Menu Log Penyimpanan -->
                        <div class="space-y-1">
                            <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('log-penyimpanan.*') ? 'bg-green-600/80 text-white shadow-lg shadow-green-600/25' : '' }}" href="{{ route('log-penyimpanan.index') }}">
                                <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-green-500/50 transition-colors duration-200">
                                    <i class="fas fa-clipboard-list text-xs"></i>
                                </div>
                                <span class="text-sm font-medium">Log Penyimpanan</span>
                            </a>
                        </div>

                        @if(Auth::guard('web')->user() && (Auth::guard('web')->user()->isSupervisor() || Auth::guard('web')->user()->isAdmin()))
                        <!-- Menu Pengangkutan Limbah -->
                        <div class="space-y-1">
                            <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('pengangkutan-limbah.*') ? 'bg-yellow-600/80 text-white shadow-lg shadow-yellow-600/25' : '' }}" href="{{ route('pengangkutan-limbah.index') }}">
                                <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-yellow-500/50 transition-colors duration-200">
                                    <i class="fas fa-truck text-xs"></i>
                                </div>
                                <span class="text-sm font-medium">Pengangkutan Limbah</span>
                            </a>
                        </div>
                        @endif
                        
                         <!-- Section LAPORAN -->
                         <div class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/30 mb-2 mt-4">
                             <i class="fas fa-chart-bar mr-1"></i>LAPORAN
                         </div>
                         
                         <!-- Menu Laporan Bulanan -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('reports.monthly') ? 'bg-emerald-600/80 text-white shadow-lg shadow-emerald-600/25' : '' }}" href="{{ route('reports.monthly') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-emerald-500/50 transition-colors duration-200">
                                     <i class="fas fa-calendar-alt text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Laporan Bulanan</span>
                             </a>
                         </div>
                         
                         <!-- Menu Laporan Status -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('reports.status') ? 'bg-emerald-600/80 text-white shadow-lg shadow-emerald-600/25' : '' }}" href="{{ route('reports.status') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-emerald-500/50 transition-colors duration-200">
                                     <i class="fas fa-info-circle text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Laporan Status</span>
                             </a>
                         </div>
                         
                         <!-- Menu Laporan Jenis Limbah -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('reports.waste-type') ? 'bg-emerald-600/80 text-white shadow-lg shadow-emerald-600/25' : '' }}" href="{{ route('reports.waste-type') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-emerald-500/50 transition-colors duration-200">
                                     <i class="fas fa-recycle text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Laporan Jenis Limbah</span>
                             </a>
                         </div>
                         
                         <!-- Menu Laporan Perusahaan -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('reports.company') ? 'bg-emerald-600/80 text-white shadow-lg shadow-emerald-600/25' : '' }}" href="{{ route('reports.company') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-emerald-500/50 transition-colors duration-200">
                                     <i class="fas fa-building text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Laporan Perusahaan</span>
                             </a>
                         </div>
                         
                         <!-- Menu Laporan Unit -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('reports.unit') ? 'bg-emerald-600/80 text-white shadow-lg shadow-emerald-600/25' : '' }}" href="{{ route('reports.unit') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-emerald-500/50 transition-colors duration-200">
                                     <i class="fas fa-industry text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Laporan Unit</span>
                             </a>
                         </div>
                         <!-- Section MASTER DATA -->
                         <div class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/30 mb-2 mt-4">
                             <i class="fas fa-database mr-1"></i>MASTER DATA
                         </div>
                         
                         <!-- Menu Perusahaan Penghasil -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('perusahaan-penghasil.*') ? 'bg-purple-600/80 text-white shadow-lg shadow-purple-600/25' : '' }}" href="{{ route('perusahaan-penghasil.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-purple-500/50 transition-colors duration-200">
                                     <i class="fas fa-building text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Perusahaan Penghasil</span>
                             </a>
                         </div>
                         
                         <!-- Menu Unit Pembangkit -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('unit-pembangkit.*') ? 'bg-purple-600/80 text-white shadow-lg shadow-purple-600/25' : '' }}" href="{{ route('unit-pembangkit.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-purple-500/50 transition-colors duration-200">
                                     <i class="fas fa-map-marker-alt text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Unit Pembangkit</span>
                             </a>
                         </div>
                         
                         <!-- Menu Pengelolaan Users -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('pengguna-sistem.*') ? 'bg-purple-600/80 text-white shadow-lg shadow-purple-600/25' : '' }}" href="{{ route('pengguna-sistem.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-purple-500/50 transition-colors duration-200">
                                     <i class="fas fa-users text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Pengelolaan Users</span>
                             </a>
                         </div>
                         
                         <!-- Menu Peran Pengguna -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('peran-pengguna.*') ? 'bg-purple-600/80 text-white shadow-lg shadow-purple-600/25' : '' }}" href="{{ route('peran-pengguna.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-purple-500/50 transition-colors duration-200">
                                     <i class="fas fa-user-tag text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Peran Pengguna</span>
                             </a>
                         </div>
                         
                         <!-- Section LIMBAH -->
                         <div class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/30 mb-2 mt-4">
                             <i class="fas fa-recycle mr-1"></i>LIMBAH
                         </div>
                         
                         <!-- Menu Jenis Limbah -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('jenis-limbah.*') ? 'bg-orange-600/80 text-white shadow-lg shadow-orange-600/25' : '' }}" href="{{ route('jenis-limbah.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-orange-500/50 transition-colors duration-200">
                                     <i class="fas fa-file-alt text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Jenis Limbah</span>
                             </a>
                         </div>
                         
                         <!-- Menu Karakteristik Limbah -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('karakteristik-limbah.*') ? 'bg-orange-600/80 text-white shadow-lg shadow-orange-600/25' : '' }}" href="{{ route('karakteristik-limbah.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-orange-500/50 transition-colors duration-200">
                                     <i class="fas fa-tags text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Karakteristik Limbah</span>
                             </a>
                         </div>
                         
                         <!-- Menu Kategori Kegiatan Limbah -->
                         <div class="space-y-1">
                             <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('kategori-kegiatan-sumber.*') ? 'bg-orange-600/80 text-white shadow-lg shadow-orange-600/25' : '' }}" href="{{ route('kategori-kegiatan-sumber.index') }}">
                                 <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-orange-500/50 transition-colors duration-200">
                                     <i class="fas fa-list text-xs"></i>
                                 </div>
                                 <span class="text-sm font-medium">Kategori Kegiatan Limbah</span>
                             </a>
                         </div>
                         

                         
                         <!-- Section PENGATURAN -->
                          <div class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/30 mb-2 mt-4">
                              <i class="fas fa-cog mr-1"></i>PENGATURAN
                          </div>
                          
                          @if(Auth::guard('web')->user() && Auth::guard('web')->user()->isSuperAdmin())
                          <!-- Menu System Settings -->
                          <div class="space-y-1">
                              <a class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-slate-700/50 hover:text-white hover:shadow-lg hover:scale-105 {{ request()->routeIs('application-settings.*') ? 'bg-blue-600/80 text-white shadow-lg shadow-blue-600/25' : '' }}" href="{{ route('application-settings.index') }}">
                                  <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-blue-500/50 transition-colors duration-200">
                                      <i class="fas fa-cogs text-xs"></i>
                                  </div>
                                  <span class="text-sm font-medium">System Settings</span>
                              </a>
                          </div>
                          @endif
                          
                          <!-- Menu Logout -->
                          <div class="space-y-1">
                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <button type="submit" class="nav-link group flex items-center px-3 py-2 text-slate-200 rounded-lg transition-all duration-200 hover:bg-red-600/50 hover:text-white hover:shadow-lg hover:scale-105 w-full text-left">
                                      <div class="w-6 h-6 bg-slate-700/50 rounded-md flex items-center justify-center mr-2 group-hover:bg-red-500/50 transition-colors duration-200">
                                          <i class="fas fa-sign-out-alt text-xs"></i>
                                      </div>
                                      <span class="text-sm font-medium">Logout</span>
                                  </button>
                              </form>
                          </div>
                    </div>
                </div>
            </nav>
        </div>


        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300 ease-in-out">
            <!-- Top Navbar -->
            <nav class="backdrop-blur-sm border-b shadow-sm sticky top-0 z-40" style="background-color: var(--navbar-bg); border-color: var(--border-primary);">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Left side -->
                    <div class="flex items-center space-x-4">
                        <button class="md:hidden focus:outline-none p-2 rounded-lg transition-colors duration-200" id="sidebarToggle" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'; this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.color='var(--text-secondary)'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-bars text-lg"></i>
                         </button>
                         <div class="flex items-center">
                            <div class="w-32 h-16 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-32 h-16 object-contain">
                            </div>
                        </div>                    </div>
                    
                    <!-- Right side -->                    <div class="flex items-center space-x-4">                        <!-- Search -->
                        <div class="hidden md:flex items-center">
                            <div class="relative group">
                                <input class="w-64 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-sm" type="text" placeholder="Cari data..." style="background-color: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text);">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search transition-colors duration-200" style="color: var(--text-tertiary);"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dark Mode Toggle -->
                         <div class="relative">
                             <button class="flex items-center justify-center w-10 h-10 focus:outline-none rounded-xl transition-all duration-200" id="darkModeToggle" onclick="toggleDarkMode()" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'; this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.color='var(--text-secondary)'; this.style.backgroundColor='transparent'">
                                 <i class="fas fa-sun text-lg" id="darkModeIcon"></i>
                             </button>
                         </div>
                         
                         <!-- Notifications -->
                         <div class="relative">
                             <button class="flex items-center justify-center w-10 h-10 focus:outline-none rounded-xl transition-all duration-200 relative" id="notification-bell" onclick="toggleNotificationDropdown()" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'; this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.color='var(--text-secondary)'; this.style.backgroundColor='transparent'">
                                 <i class="far fa-bell text-lg"></i>
                                 <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold" id="notification-count" style="display: none;">0</span>
                             </button>
                            <div class="absolute right-0 mt-3 w-96 rounded-2xl shadow-2xl z-50 hidden overflow-hidden" id="notification-dropdown" style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
                                 <div class="px-6 py-4 border-b" style="background: linear-gradient(to right, var(--bg-tertiary), var(--bg-tertiary)); border-color: var(--border-primary);">
                                     <div class="flex items-center justify-between">
                                         <h3 class="font-bold" id="notification-header" style="color: var(--text-primary);">0 Notifikasi</h3>
                                         <button class="text-blue-600 hover:text-blue-800 text-sm font-medium" onclick="refreshNotifications()">
                                             <i class="fas fa-sync-alt mr-1"></i>Refresh
                                         </button>
                                     </div>
                                 </div>
                                <div class="max-h-80 overflow-y-auto" id="notification-list">
                                    <!-- Notifications will be loaded here -->
                                </div>
                                <div class="border-t border-gray-100 bg-gray-50">
                                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-center w-full px-6 py-3 text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 font-medium">
                                        Lihat Semua Notifikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Dropdown -->                        <div class="relative">                            <button class="flex items-center space-x-3 focus:outline-none p-2 rounded-xl transition-all duration-200" id="userDropdown" onclick="toggleUserDropdown()" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'; this.style.backgroundColor='var(--hover-bg)';" onmouseout="this.style.color='var(--text-secondary)'; this.style.backgroundColor='transparent';">                                <div class="flex items-center space-x-3">                                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center text-sm font-bold shadow-lg">                                        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name, 0, 1)) }}                                    </div>                                    <div class="hidden md:block text-left">
                                        <div class="font-semibold text-sm" style="color: var(--text-primary);">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                                        <div class="text-xs" style="color: var(--text-secondary);">{{ Auth::user()->unitPembangkit->nama_unit ?? 'N/A' }}</div>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs" style="color: var(--text-secondary);"></i>
                                </div>
                            </button>
                            <div class="absolute right-0 mt-3 w-80 rounded-2xl shadow-2xl z-50 hidden overflow-hidden" id="userDropdownMenu" style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
                                <div class="px-6 py-5 border-b" style="background: linear-gradient(135deg, var(--gradient-start, #dbeafe) 0%, var(--gradient-end, #e0e7ff) 100%); border-color: var(--border-primary);">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg">
                                            {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-lg" style="color: var(--text-primary);">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                                            <div class="text-sm" style="color: var(--text-secondary);">{{ Auth::user()->email_address ?? Auth::user()->email }}</div>
                                            <div class="text-sm font-medium mt-1" style="color: var(--accent-primary, #2563eb);">{{ Auth::user()->unitPembangkit->nama_unit ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>                                <div class="py-3">
                                    <a class="flex items-center px-6 py-3 transition-all duration-200 group" href="{{ route('profile.show') }}" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.color='var(--accent-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 transition-colors duration-200" style="background-color: var(--accent-bg, #dbeafe);">
                                            <i class="fas fa-user text-sm" style="color: var(--accent-primary, #2563eb);"></i>
                                        </div>
                                        <span class="font-medium">Lihat Profile</span>
                                    </a>
                                    <a class="flex items-center px-6 py-3 transition-all duration-200 group" href="{{ route('pengguna-sistem.index') }}" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.color='var(--accent-secondary, #059669)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 transition-colors duration-200" style="background-color: var(--accent-bg-secondary, #d1fae5);">
                                            <i class="fas fa-users text-sm" style="color: var(--accent-secondary, #059669);"></i>
                                        </div>
                                        <span class="font-medium">Kelola Users</span>
                                    </a>
                                </div>                                <div class="border-t" style="border-color: var(--border-primary); background-color: var(--bg-secondary, #f9fafb);">
                                    <form method="POST" action="{{ route('logout') }}" class="hidden" id="logout-form">
                                        @csrf
                                    </form>
                                    <button class="flex items-center w-full px-6 py-3 transition-all duration-200 group" style="color: var(--danger-primary, #dc2626);" onmouseover="this.style.backgroundColor='var(--danger-bg, #fef2f2)'; this.style.color='var(--danger-hover, #b91c1c)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--danger-primary, #dc2626)';" onclick="handleLogout(event); return false;">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 transition-colors duration-200" style="background-color: var(--danger-bg-light, #fee2e2);">
                                            <i class="fas fa-sign-out-alt text-sm" style="color: var(--danger-primary, #dc2626);"></i>
                                        </div>
                                        <span class="font-medium">Logout</span>
                                    </button>
                                </div>                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->            <main class="flex-1 overflow-y-auto" style="background-color: var(--bg-primary);">                <div class="py-4">
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="text-green-600 hover:text-green-800 focus:outline-none" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ session('warning') }}
                            </div>
                            <button type="button" class="text-yellow-600 hover:text-yellow-800 focus:outline-none" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ session('info') }}
                            </div>
                            <button type="button" class="text-blue-600 hover:text-blue-800 focus:outline-none" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="py-6 mt-auto" style="background-color: var(--card-bg); border-top: 1px solid var(--border-primary);">
                <div class="px-4">
                    <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-recycle text-white text-xs"></i>
                            </div>
                            <span>Copyright &copy; WASPRO {{ date('Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="#" class="hover:text-blue-600 transition-colors duration-200 font-medium">Privacy Policy</a>
                            <span style="color: var(--border-primary);">&middot;</span>
                            <a href="#" class="hover:text-blue-600 transition-colors duration-200 font-medium">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        @else
        <!-- Guest Layout -->
        <div class="min-h-screen bg-gray-50">
            <!-- Top Navbar for Guests -->
            <nav class="bg-white shadow-sm border-b border-gray-200">                <div class="px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                                <i class="fas fa-recycle mr-2 text-blue-600"></i>
                                WASPRO
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Login
                                </a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                    Register
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content for Guests -->
            <main class="py-6">
                @if(session('success'))
                    <div class="px-4 mb-4">
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="text-green-600 hover:text-green-800 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="px-4 mb-4">
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
        @endauth
    </div>

    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('-translate-x-full');
            mainContent.classList.toggle('md:ml-0');
        }
        
        // Toggle submenu
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + '-icon');
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                icon.style.transform = 'rotate(180deg)';
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                icon.style.transform = 'rotate(0deg)';
            }
        }
        
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdownMenu');
            dropdown.classList.toggle('hidden');
        }
        
        // Toggle mobile menu
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
        
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
        
        // Notification functions
         function toggleNotificationDropdown() {
             const dropdown = document.getElementById('notification-dropdown');
             dropdown.classList.toggle('hidden');
         }
        
        function loadNotifications() {
            fetch('/notifications/get-count')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.count);
                    updateNotificationHeader(data.count);
                })
                .catch(error => console.error('Error loading notification count:', error));
                
            fetch('/notifications/get-expiry-notifications')
                .then(response => response.json())
                .then(data => {
                    updateNotificationList(data.notifications);
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        function updateNotificationBadge(count) {
             const badge = document.getElementById('notification-count');
             const sidebarBadge = document.getElementById('sidebar-notification-badge');
             
             if (count > 0) {
                 badge.textContent = count > 99 ? '99+' : count;
                 badge.style.display = 'flex';
                 
                 if (sidebarBadge) {
                     sidebarBadge.style.display = 'flex';
                 }
             } else {
                 badge.style.display = 'none';
                 
                 if (sidebarBadge) {
                     sidebarBadge.style.display = 'none';
                 }
             }
         }
        
        function updateNotificationHeader(count) {
            const header = document.getElementById('notification-header');
            header.textContent = count + ' Notifikasi';
        }
        
        function updateNotificationList(notifications) {
            const list = document.getElementById('notification-list');
            
            if (notifications.length === 0) {
                list.innerHTML = '<div class="px-6 py-8 text-center text-gray-500"><i class="fas fa-bell-slash text-3xl mb-2"></i><p>Tidak ada notifikasi</p></div>';
                return;
            }
            
            let html = '';
            notifications.forEach(notification => {
                const statusClass = getStatusClass(notification.expiry_status);
                const statusText = getStatusText(notification.expiry_status);
                const timeText = getTimeText(notification.days_until_expiry);
                
                html += `
                    <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 ${statusClass} rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    ${notification.kode_identitas}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    ${notification.jenis_limbah_nama}
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusClass.replace('bg-', 'bg-').replace('-500', '-100')} ${statusClass.replace('bg-', 'text-').replace('-500', '-800')}">
                                        ${statusText}
                                    </span>
                                    <span class="text-xs text-gray-500">${timeText}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            list.innerHTML = html;
        }
        
        function getStatusClass(status) {
            switch(status) {
                case 'expired': return 'bg-red-500';
                case 'urgent': return 'bg-orange-500';
                case 'critical': return 'bg-yellow-500';
                case 'warning': return 'bg-blue-500';
                default: return 'bg-gray-500';
            }
        }
        
        function getStatusText(status) {
            switch(status) {
                case 'expired': return 'Kadaluarsa';
                case 'urgent': return 'Sangat Mendesak';
                case 'critical': return 'Kritis';
                case 'warning': return 'Peringatan';
                default: return 'Normal';
            }
        }
        
        function getTimeText(days) {
            if (days < 0) {
                return Math.abs(days) + ' hari yang lalu';
            } else if (days === 0) {
                return 'Hari ini';
            } else {
                return days + ' hari lagi';
            }
        }
        
        function refreshNotifications() {
            loadNotifications();
        }
        
        // Close notification dropdown when clicking outside
         document.addEventListener('click', function(event) {
             const notificationDropdown = document.getElementById('notification-dropdown');
             const notificationButton = event.target.closest('#notification-bell');
             
             if (!notificationButton && notificationDropdown && !notificationDropdown.contains(event.target)) {
                 notificationDropdown.classList.add('hidden');
             }
         });

        // Dark Mode Functions
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateDarkModeIcon(newTheme);
        }
        
        function updateDarkModeIcon(theme) {
            const icon = document.getElementById('darkModeIcon');
            if (theme === 'dark') {
                icon.className = 'fas fa-moon text-lg';
            } else {
                icon.className = 'fas fa-sun text-lg';
            }
        }
        
        function initDarkMode() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            
            document.documentElement.setAttribute('data-theme', theme);
            updateDarkModeIcon(theme);
        }
        
        // Initialize dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
        });
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                const theme = e.matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', theme);
                updateDarkModeIcon(theme);
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            
            // Mobile menu button event
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', toggleMobileMenu);
            }
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const userDropdown = document.getElementById('userDropdownMenu');
                const userButton = event.target.closest('#userDropdown');
                
                if (!userButton && userDropdown && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
            
            // Safari-compatible logout handler
             window.handleLogout = function(e) {
                 e.preventDefault();
                 if (confirm('Apakah Anda yakin ingin logout?')) {
                     document.getElementById('logout-form').submit();
                 }
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
             
             // Load notifications on page load
             loadNotifications();
             
             // Auto-refresh notifications every 30 seconds
             setInterval(loadNotifications, 30000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
