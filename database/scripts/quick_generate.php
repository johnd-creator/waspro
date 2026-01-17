#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Illuminate\Support\Facades\Hash;

echo "\n🚀 Quick Data Generator for WASPRO\n";
echo "===================================\n\n";

// 1. Create Roles
echo "📋 Creating roles...\n";
$roles = [
    ['nama_peran' => 'Super Admin', 'deskripsi' => 'Akses penuh', 'is_active' => true],
    ['nama_peran' => 'Administrator', 'deskripsi' => 'Admin unit', 'is_active' => true],
    ['nama_peran' => 'Supervisor', 'deskripsi' => 'Supervisor', 'is_active' => true],
    ['nama_peran' => 'Operator', 'deskripsi' => 'Operator', 'is_active' => true],
    ['nama_peran' => 'Viewer', 'deskripsi' => 'Viewer', 'is_active' => true],
];
foreach ($roles as $role) {
    PeranPengguna::firstOrCreate(['nama_peran' => $role['nama_peran']], $role);
}
echo '   ✓ Roles: '.PeranPengguna::count()."\n";

// 2. Create Unit
echo "🏢 Creating unit...\n";
$unit = UnitPembangkit::firstOrCreate(
    ['nama_unit' => 'Unit Pembangkit Jakarta'],
    ['lokasi_unit' => 'Jakarta, Indonesia', 'kapasitas_mw' => 500]
);
echo '   ✓ Units: '.UnitPembangkit::count()."\n";

// 3. Create Jenis Limbah
echo "🗑️  Creating jenis limbah...\n";
for ($i = 0; $i < 10; $i++) {
    JenisLimbah::factory()->create([
        'biaya_pengangkutan_per_kg' => rand(50000, 150000),
        'status_aktif' => $i < 8,
    ]);
}
echo '   ✓ Jenis Limbah: '.JenisLimbah::count()."\n";

// 4. Create Users
echo "👥 Creating users...\n";
$adminRole = PeranPengguna::where('nama_peran', 'Administrator')->first();
$supervisorRole = PeranPengguna::where('nama_peran', 'Supervisor')->first();
$operatorRole = PeranPengguna::where('nama_peran', 'Operator')->first();

// Create admin
$admin = PenggunaSistem::create([
    'nama_lengkap' => 'Admin Test',
    'email_address' => 'admin@waspro.com',
    'kata_sandi_hash' => Hash::make('password'),
    'unit_id' => $unit->unit_id,
    'aktif' => true,
    'email_verified_at' => now(),
]);
$admin->peranPengguna()->attach($adminRole->peran_id);

// Create supervisor
$supervisor = PenggunaSistem::create([
    'nama_lengkap' => 'Supervisor Test',
    'email_address' => 'supervisor@waspro.com',
    'kata_sandi_hash' => Hash::make('password'),
    'unit_id' => $unit->unit_id,
    'aktif' => true,
    'email_verified_at' => now(),
]);
$supervisor->peranPengguna()->attach($supervisorRole->peran_id);

// Create operators
for ($i = 1; $i <= 5; $i++) {
    $operator = PenggunaSistem::create([
        'nama_lengkap' => "Operator {$i}",
        'email_address' => "operator{$i}@waspro.com",
        'kata_sandi_hash' => Hash::make('password'),
        'unit_id' => $unit->unit_id,
        'aktif' => true,
        'email_verified_at' => now(),
    ]);
    $operator->peranPengguna()->attach($operatorRole->peran_id);
}
echo '   ✓ Users: '.PenggunaSistem::count()."\n";

// 5. Create Logs
echo "📦 Creating logs...\n";
for ($i = 0; $i < 30; $i++) {
    LogPenyimpananLimbah::factory()->tersimpan()->create();
}
for ($i = 0; $i < 15; $i++) {
    LogPenyimpananLimbah::factory()->diangkut()->create();
}
for ($i = 0; $i < 5; $i++) {
    LogPenyimpananLimbah::factory()->expired()->create();
}
echo '   ✓ Logs: '.LogPenyimpananLimbah::withoutGlobalScopes()->count()."\n";

echo "\n===================================\n";
echo "✅ Data generation complete!\n";
echo "===================================\n";
echo "\n🔑 Login Credentials:\n";
echo "   Email: admin@waspro.com\n";
echo "   Password: password\n\n";
