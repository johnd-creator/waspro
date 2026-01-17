# WASPRO Development Data Generation Guide

## Quick Start

Run this command to generate all development data:

```bash
php artisan db:seed --class=DevelopmentDataSeeder
```

## Alternative: Manual Generation via Tinker

If the seeder doesn't work, you can generate data manually using Laravel Tinker:

### Step 1: Start Tinker
```bash
php artisan tinker
```

### Step 2: Generate Roles
```php
use App\Models\PeranPengguna;

$roles = [
    ['nama_peran' => 'Super Admin', 'deskripsi' => 'Akses penuh ke seluruh sistem', 'is_active' => true],
    ['nama_peran' => 'Administrator', 'deskripsi' => 'Dapat mengelola semua data dalam unit', 'is_active' => true],
    ['nama_peran' => 'Supervisor', 'deskripsi' => 'Dapat menyetujui dan memverifikasi data', 'is_active' => true],
    ['nama_peran' => 'Operator', 'deskripsi' => 'Dapat mengelola data limbah', 'is_active' => true],
    ['nama_peran' => 'Viewer', 'deskripsi' => 'Hanya dapat melihat data', 'is_active' => true],
];

foreach ($roles as $role) {
    PeranPengguna::firstOrCreate(['nama_peran' => $role['nama_peran']], $role);
}

echo "✓ Roles created\n";
```

### Step 3: Generate Units
```php
use App\Models\UnitPembangkit;

$units = [
    ['nama_unit' => 'Unit Pembangkit Jakarta Pusat', 'lokasi_unit' => 'Jakarta Pusat', 'kapasitas_mw' => 500],
    ['nama_unit' => 'Unit Pembangkit Surabaya', 'lokasi_unit' => 'Surabaya', 'kapasitas_mw' => 400],
    ['nama_unit' => 'Unit Pembangkit Medan', 'lokasi_unit' => 'Medan', 'kapasitas_mw' => 350],
    ['nama_unit' => 'Unit Pembangkit Bandung', 'lokasi_unit' => 'Bandung', 'kapasitas_mw' => 300],
    ['nama_unit' => 'Unit Pembangkit Semarang', 'lokasi_unit' => 'Semarang', 'kapasitas_mw' => 250],
];

foreach ($units as $unit) {
    UnitPembangkit::firstOrCreate(['nama_unit' => $unit['nama_unit']], $unit);
}

echo "✓ Units created\n";
```

### Step 4: Generate Supporting Data
```php
use App\Models\KarakteristikLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\PerusahaanPenghasil;

KarakteristikLimbah::factory()->count(5)->create();
KategoriKegiatanSumber::factory()->count(5)->create();
PerusahaanPenghasil::factory()->count(10)->create();

echo "✓ Supporting data created\n";
```

### Step 5: Generate Jenis Limbah
```php
use App\Models\JenisLimbah;

// High cost (5)
for ($i = 0; $i < 5; $i++) {
    JenisLimbah::factory()->create([
        'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 100000, 150000),
        'status_aktif' => true,
    ]);
}

// Medium cost (8)
for ($i = 0; $i < 8; $i++) {
    JenisLimbah::factory()->create([
        'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 80000, 100000),
        'status_aktif' => true,
    ]);
}

// Low cost (7, with 5 inactive)
for ($i = 0; $i < 7; $i++) {
    JenisLimbah::factory()->create([
        'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 50000, 80000),
        'status_aktif' => $i < 2,
    ]);
}

echo "✓ Created 20 jenis limbah\n";
```

### Step 6: Generate Users with Roles
```php
use App\Models\PenggunaSistem;
use Illuminate\Support\Facades\Hash;

$roleModels = [
    'Super Admin' => PeranPengguna::where('nama_peran', 'Super Admin')->first(),
    'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
    'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
    'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
    'Viewer' => PeranPengguna::where('nama_peran', 'Viewer')->first(),
];

$allUnits = UnitPembangkit::all();

// Super Admin (1)
$superAdmin = PenggunaSistem::create([
    'nama_lengkap' => 'Super Administrator',
    'email_address' => 'superadmin@waspro.com',
    'kata_sandi_hash' => Hash::make('password'),
    'unit_id' => null,
    'aktif' => true,
    'email_verified_at' => now(),
]);
$superAdmin->peranPengguna()->attach($roleModels['Super Admin']->peran_id);

echo "✓ Created Super Admin\n";

// Administrators (5, 1 per unit)
$adminCount = 0;
foreach ($allUnits as $unit) {
    if ($adminCount >= 5) break;
    
    $admin = PenggunaSistem::create([
        'nama_lengkap' => 'Administrator ' . $unit->nama_unit,
        'email_address' => 'admin' . $adminCount . '@waspro.com',
        'kata_sandi_hash' => Hash::make('password'),
        'unit_id' => $unit->unit_id,
        'aktif' => true,
        'email_verified_at' => now(),
    ]);
    $admin->peranPengguna()->attach($roleModels['Administrator']->peran_id);
    $adminCount++;
}

echo "✓ Created {$adminCount} Administrators\n";

// Supervisors (10, 2 per unit)
$supervisorCount = 0;
foreach ($allUnits as $unit) {
    for ($i = 0; $i < 2; $i++) {
        if ($supervisorCount >= 10) break 2;
        
        $supervisor = PenggunaSistem::create([
            'nama_lengkap' => 'Supervisor ' . ($supervisorCount + 1),
            'email_address' => 'supervisor' . ($supervisorCount + 1) . '@waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $supervisor->peranPengguna()->attach($roleModels['Supervisor']->peran_id);
        $supervisorCount++;
    }
}

echo "✓ Created {$supervisorCount} Supervisors\n";

// Operators (30, 6 per unit)
$operatorCount = 0;
foreach ($allUnits as $unit) {
    for ($i = 0; $i < 6; $i++) {
        if ($operatorCount >= 30) break 2;
        
        $operator = PenggunaSistem::create([
            'nama_lengkap' => 'Operator ' . ($operatorCount + 1),
            'email_address' => 'operator' . ($operatorCount + 1) . '@waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $operator->peranPengguna()->attach($roleModels['Operator']->peran_id);
        $operatorCount++;
    }
}

echo "✓ Created {$operatorCount} Operators\n";

// Viewers (4, 1 per unit for first 4 units)
$viewerCount = 0;
foreach ($allUnits->take(4) as $unit) {
    $viewer = PenggunaSistem::create([
        'nama_lengkap' => 'Viewer ' . ($viewerCount + 1),
        'email_address' => 'viewer' . ($viewerCount + 1) . '@waspro.com',
        'kata_sandi_hash' => Hash::make('password'),
        'unit_id' => $unit->unit_id,
        'aktif' => true,
        'email_verified_at' => now(),
    ]);
    $viewer->peranPengguna()->attach($roleModels['Viewer']->peran_id);
    $viewerCount++;
}

echo "✓ Created {$viewerCount} Viewers\n";
echo "📊 Total users: " . PenggunaSistem::count() . "\n";
```

### Step 7: Generate Log Penyimpanan Limbah
```php
use App\Models\LogPenyimpananLimbah;

// Tersimpan (80)
echo "Creating tersimpan logs...\n";
for ($i = 0; $i < 80; $i++) {
    LogPenyimpananLimbah::factory()->tersimpan()->create();
    if ($i % 20 == 19) echo "  ... " . ($i + 1) . "/80\n";
}

// Diangkut (70)
echo "Creating diangkut logs...\n";
for ($i = 0; $i < 70; $i++) {
    LogPenyimpananLimbah::factory()->diangkut()->create();
    if ($i % 20 == 19) echo "  ... " . ($i + 1) . "/70\n";
}

// Expired (50)
echo "Creating expired logs...\n";
for ($i = 0; $i < 50; $i++) {
    LogPenyimpananLimbah::factory()->expired()->create();
    if ($i % 20 == 19) echo "  ... " . ($i + 1) . "/50\n";
}

echo "✓ Created 200 logs\n";
echo "📊 Total logs: " . LogPenyimpananLimbah::withoutGlobalScopes()->count() . "\n";
```

### Step 8: Update Approval Statuses
```php
$approvers = PenggunaSistem::whereHas('peranPengguna', function($q) {
    $q->whereIn('nama_peran', ['Supervisor', 'Administrator', 'Super Admin']);
})->where('aktif', true)->get();

$allLogs = LogPenyimpananLimbah::withoutGlobalScopes()->get();
$processed = 0;

foreach ($allLogs as $log) {
    $rand = rand(1, 100);
    
    if ($rand <= 50) {
        // 50% pending
        $log->update([
            'approval_status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_reason' => null,
        ]);
    } elseif ($rand <= 80) {
        // 30% approved
        $approver = $approvers->random();
        $log->update([
            'approval_status' => 'approved',
            'approved_by' => $approver->user_id,
            'approved_at' => now()->subDays(rand(1, 30)),
            'rejected_reason' => null,
        ]);
    } else {
        // 20% rejected
        $approver = $approvers->random();
        $reasons = [
            'Data tidak lengkap',
            'Dokumen tidak sesuai',
            'Informasi limbah tidak valid',
            'Perlu verifikasi ulang',
        ];
        $log->update([
            'approval_status' => 'rejected',
            'approved_by' => $approver->user_id,
            'approved_at' => now()->subDays(rand(1, 30)),
            'rejected_reason' => $reasons[array_rand($reasons)],
        ]);
    }
    
    $processed++;
    if ($processed % 50 == 0) {
        echo "  ... processed {$processed}/" . $allLogs->count() . "\n";
    }
}

echo "✓ Updated approval statuses\n";
```

### Step 9: Generate Audit Logs
```php
use App\Models\AuditLog;

AuditLog::factory()->createAction()->count(30)->create();
AuditLog::factory()->update()->count(90)->create();
AuditLog::factory()->delete()->count(30)->create();

echo "✓ Created 150 audit logs\n";
```

### Step 10: Generate Application Settings
```php
use App\Models\ApplicationSetting;

$settings = [
    ['key' => 'app_name', 'value' => 'WASPRO', 'type' => 'string', 'category' => 'general', 'description' => 'Nama aplikasi', 'is_active' => true],
    ['key' => 'limbah_expiry_days', 'value' => '90', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Maksimal hari penyimpanan limbah', 'is_active' => true],
    ['key' => 'expiry_warning_days', 'value' => '30', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Hari peringatan sebelum kadaluarsa', 'is_active' => true],
    ['key' => 'expiry_critical_days', 'value' => '7', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Hari kritis sebelum kadaluarsa', 'is_active' => true],
];

foreach ($settings as $setting) {
    ApplicationSetting::firstOrCreate(['key' => $setting['key']], $setting);
}

echo "✓ Created application settings\n";
```

### Step 11: Verify Data
```php
echo "\n📊 Final Summary:\n";
echo "   - Roles: " . PeranPengguna::count() . "\n";
echo "   - Units: " . UnitPembangkit::count() . "\n";
echo "   - Users: " . PenggunaSistem::count() . "\n";
echo "   - Jenis Limbah: " . JenisLimbah::count() . "\n";
echo "   - Log Penyimpanan: " . LogPenyimpananLimbah::withoutGlobalScopes()->count() . "\n";
echo "   - Audit Logs: " . AuditLog::count() . "\n";
echo "   - Application Settings: " . ApplicationSetting::count() . "\n";

// Approval status breakdown
$pending = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'pending')->count();
$approved = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'approved')->count();
$rejected = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'rejected')->count();

echo "\n   Approval Status:\n";
echo "   - Pending: {$pending}\n";
echo "   - Approved: {$approved}\n";
echo "   - Rejected: {$rejected}\n";

// Expiry status breakdown
$critical = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Critical')->count();
$warning = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Warning')->count();
$safe = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Safe')->count();
$expired = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Expired')->count();

echo "\n   Expiry Status:\n";
echo "   - Critical: {$critical}\n";
echo "   - Warning: {$warning}\n";
echo "   - Safe: {$safe}\n";
echo "   - Expired: {$expired}\n";
```

## Notes

- All users have password: `password`
- Super Admin email: `superadmin@waspro.com`
- Administrator emails: `admin0@waspro.com` through `admin4@waspro.com`
- Supervisor emails: `supervisor1@waspro.com` through `supervisor10@waspro.com`
- Operator emails: `operator1@waspro.com` through `operator30@waspro.com`
- Viewer emails: `viewer1@waspro.com` through `viewer4@waspro.com`

## Troubleshooting

If you encounter errors:

1. **Foreign key constraint errors**: Make sure to run migrations first
   ```bash
   php artisan migrate
   ```

2. **Factory not found errors**: Clear the cache
   ```bash
   php artisan clear-compiled
   composer dump-autoload
   ```

3. **Memory limit errors**: Increase PHP memory limit
   ```bash
   php -d memory_limit=512M artisan tinker
   ```

4. **Timeout errors**: Generate data in smaller batches (e.g., 20 logs at a time instead of 80)
