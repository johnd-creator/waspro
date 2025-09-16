<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 font-inter">
    <div class="min-h-screen flex items-center justify-center p-4 mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full grid md:grid-cols-2">

            <!-- Left Side - Branding -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 md:p-12 flex flex-col justify-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
                </div>

                <!-- Content -->
                <div class="relative z-10 text-white">
                    <!-- Logo -->
                    <div class="mb-8">
                        <div class="flex items-center justify-center">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 mr-3">
                                <i class="fas fa-recycle text-2xl text-emerald-400"></i>
                            </div>
                            <h1 class="text-3xl font-bold">WASPRO</h1>
                        </div>
                    </div>

                    <!-- Subtitle -->
                    <div class="mb-8 text-center">
                        <h2 class="text-xl font-semibold mb-2">Sistem Manajemen Pengelolaan Limbah</h2>
                        <p class="text-slate-300 text-sm">Platform terintegrasi untuk pengelolaan limbah yang efisien dan berkelanjutan</p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-emerald-500/20 rounded-lg p-2">
                                    <i class="fas fa-shield-alt text-emerald-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Keamanan Data Terjamin</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500/20 rounded-lg p-2">
                                    <i class="fas fa-chart-line text-blue-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Dashboard Analytics</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-500/20 rounded-lg p-2">
                                    <i class="fas fa-users text-purple-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Multi-User Management</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-500/20 rounded-lg p-2">
                                    <i class="fas fa-mobile-alt text-orange-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Responsive Design</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500/20 rounded-lg p-2">
                                    <i class="fas fa-database text-green-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Data Management</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-red-500/20 rounded-lg p-2">
                                    <i class="fas fa-bell text-red-400 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">Real-time Alerts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="p-8 md:p-12 flex flex-col justify-center">


                <!-- PLN Logo -->
                <div class="mb-4 text-center w-48 h-17 mx-auto flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="PLN Logo" class="max-w-full max-h-full object-contain transform scale-90">
                </div>

                <!-- Welcome Section -->
                <div class="mb-6 text-center">
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">Selamat Datang</h2>
                    <p class="text-slate-600 text-base">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700 text-sm font-medium">{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email_address" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-envelope text-slate-400 mr-2"></i>
                            Alamat Email
                        </label>
                        <input type="email"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('email_address') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                               id="email_address"
                               name="email_address"
                               placeholder="nama@contoh.com"
                               value="{{ old('email_address') }}"
                               required
                               autocomplete="email"
                               autofocus>
                        @error('email_address')
                            <div class="mt-2 text-sm text-red-600">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-lock text-slate-400 mr-2"></i>
                            Password
                        </label>
                        <input type="password"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password Anda"
                               required
                               autocomplete="current-password">
                        @error('password')
                            <div class="mt-2 text-sm text-red-600">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                               type="checkbox"
                               name="remember"
                               id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="ml-2 text-sm font-medium text-slate-700" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-500">
                        © 2025 PLN Indonesia Power. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
