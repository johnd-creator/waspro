@extends('layouts.app')

@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
            <div class="flex flex-col gap-6 px-6 py-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl"
                         style="background-color: var(--accent-bg); color: var(--accent-primary);">
                        <span class="text-lg font-semibold">{{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Profile</h1>
                        <p class="text-sm" style="color: var(--text-secondary);">Kelola informasi akun dan keamanan</p>
                    </div>
                </div>
                <div class="rounded-xl border px-4 py-2 text-sm" style="border-color: var(--border-primary); color: var(--text-secondary);">
                    {{ $user->email_address }}
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border px-4 py-3 text-sm"
                 style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);"
                 role="alert" data-auto-dismiss="2500">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="border-b px-6 py-4" style="border-color: var(--border-primary);">
                    <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Informasi Profil</h2>
                    <p class="text-sm" style="color: var(--text-secondary);">Perbarui data profil Anda</p>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama_lengkap" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Nama Lengkap</label>
                            <input id="nama_lengkap" type="text"
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_lengkap') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required autocomplete="name" autofocus>
                            @error('nama_lengkap')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email_address" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Email</label>
                            <input id="email_address" type="email"
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email_address') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   name="email_address" value="{{ old('email_address', $user->email_address) }}" required autocomplete="email">
                            @error('email_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_id" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Unit Pembangkit</label>
                            <select id="unit_id"
                                    class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_id') border-red-500 @enderror"
                                    style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                    name="unit_id" required>
                                <option value="">Pilih Unit Pembangkit</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit->unit_id }}" {{ old('unit_id', $user->unit_id) == $unit->unit_id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }} - {{ $unit->kota }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition-all duration-200"
                                    style="background-color: var(--accent-primary);"
                                    onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                                    onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="border-b px-6 py-4" style="border-color: var(--border-primary);">
                    <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Ubah Kata Sandi</h2>
                    <p class="text-sm" style="color: var(--text-secondary);">Pastikan kata sandi tetap aman</p>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Kata Sandi Saat Ini</label>
                            <input id="current_password" type="password"
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   name="current_password" required autocomplete="current-password">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Kata Sandi Baru</label>
                            <input id="password" type="password"
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   name="password" required autocomplete="new-password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password-confirm" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Konfirmasi Kata Sandi</label>
                            <input id="password-confirm" type="password"
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition-all duration-200"
                                    style="background-color: var(--accent-primary);"
                                    onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                                    onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                Ubah Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
