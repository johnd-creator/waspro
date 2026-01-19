# Tinker Commands untuk Testing Development

Jalankan perintah-perintah ini dalam `php artisan tinker` untuk membuat sample data testing.

## Cara Menjalankan

```bash
php artisan tinker
```

Kemudian copy-paste perintah di bawah sesuai kebutuhan.

---

## 1. CEK DATA EXISTING

```php
// Cek jumlah semua data
PenggunaSistem::count()
JenisLimbah::count()
LogPenyimpananLimbah::count()
UnitPembangkit::count()
PeranPengguna::count()

// Cek user aktif
PenggunaSistem::where('aktif', true)->count()

// Cek log berdasarkan status
LogPenyimpananLimbah::groupBy('status_log')->selectRaw('status_log, count(*) as total')->get()

// Cek limbah yang akan kadaluarsa dalam 7 hari
\LogPenyimpananLimbah::where('status_log', 'Tersimpan')
    ->where('tanggal_kadaluarsa', '<=', now()->addDays(7))
    ->where('tanggal_kadaluarsa', '>', now())
    ->count()
```

---

## 2. CREATE SINGLE DATA

### Buat User Baru
```php
$user = PenggunaSistem::create([
    'nama_lengkap' => 'Test User',
    'email_address' => 'test@dev.local',
    'kata_sandi_hash' => Hash::make('password'),
    'unit_id' => 1,
    'aktif' => true,
])
```

### Buat Log Penyimpanan Baru
```php
\LogPenyimpananLimbah::create([
    'kode_identitas' => 'LOG-TEST-001',
    'tanggal_limbah_masuk' => now(),
    'jenis_limbah_id' => 1,
    'uraian_pekerjaan' => 'Testing data',
    'jumlah_limbah_kg' => 50,
    'satuan' => 'kg',
    'user_id' => 1,
    'unit_id' => 1,
    'status_log' => 'Tersimpan',
    'tanggal_kadaluarsa' => now()->addDays(30),
])
```

### Buat Jenis Limbah Baru
```php
\JenisLimbah::create([
    'kode_limbah' => 'WST-TEST',
    'nama_limbah' => 'Limbah Testing',
    'karakteristik_limbah_id' => 1,
    'kategori_kegiatan_sumber_id' => 1,
    'waktu_penyimpanan_hari' => 30,
    'status_aktif' => true,
    'biaya_pengangkutan_per_kg' => 5000,
])
```

---

## 3. CREATE MULTIPLE DATA (USING FACTORY)

### Buat 10 User
```php
PenggunaSistem::factory()->count(10)->create()
```

### Buat 10 User dengan unit tertentu
```php
PenggunaSistem::factory()->count(10)->create(['unit_id' => 1])
```

### Buat 10 User nonaktif
```php
PenggunaSistem::factory()->count(10)->inactive()->create()
```

### Buat 20 Log Penyimpanan
```php
\LogPenyimpananLimbah::factory()->count(20)->create()
```

### Buat 15 Jenis Limbah
```php
\JenisLimbah::factory()->count(15)->create()
```

### Buat 10 Perusahaan
```php
\PerusahaanPenghasil::factory()->count(10)->create()
```

---

## 4. CREATE SPECIFIC STATUS DATA

### Buat Log Tersimpan (10)
```php
\LogPenyimpananLimbah::factory()->count(10)->create([
    'status_log' => 'Tersimpan',
    'tanggal_limbah_masuk' => now()->subDays(rand(1, 30)),
])
```

### Buat Log Kadaluarsa (10)
```php
\LogPenyimpananLimbah::factory()->count(10)->create([
    'status_log' => 'Kadaluarsa',
    'tanggal_limbah_masuk' => now()->subDays(60),
    'tanggal_kadaluarsa' => now()->subDays(30),
])
```

### Buat Log Diangkut (10)
```php
\LogPenyimpananLimbah::factory()->count(10)->create([
    'status_log' => 'Diangkut',
    'tanggal_limbah_masuk' => now()->subDays(15),
    'tanggal_diangkut' => now(),
])
```

### Buat Log dengan berbagai tanggal
```php
\LogPenyimpananLimbah::factory()->count(30)->create([
    'tanggal_limbah_masuk' => now()->subDays(rand(0, 90)),
    'status_log' => ['Tersimpan', 'Diangkut', 'Kadaluarsa'][rand(0, 2)],
])
```

---

## 5. UPDATE DATA

### Update status log
```php
\LogPenyimpananLimbah::where('status_log', 'Tersimpan')
    ->where('tanggal_kadaluarsa', '<', now())
    ->update(['status_log' => 'Kadaluarsa'])
```

### Update semua user menjadi aktif
```php
PenggunaSistem::where('aktif', false)->update(['aktif' => true])
```

### Tambah peran ke user
```php
$user = PenggunaSistem::find(1)
$peran = \PeranPengguna::where('nama_peran', 'Super Admin')->first()
$user->peranPengguna()->attach($peran->peran_id)
```

---

## 6. DELETE DATA

### Hapus log lebih dari 1 tahun
```php
\LogPenyimpananLimbah::where('tanggal_limbah_masuk', '<', now()->subYear())->delete()
```

### Hapus user nonaktif
```php
PenggunaSistem::where('aktif', false)->delete()
```

### Hapus log kadaluarsa (HATI-HATI)
```php
\LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')->delete()
```

---

## 7. QUERY & FILTER

### Dapatkan log yang akan kadaluarsa dalam 7 hari
```php
$logs = \LogPenyimpananLimbah::where('status_log', 'Tersimpan')
    ->where('tanggal_kadaluarsa', '<=', now()->addDays(7))
    ->where('tanggal_kadaluarsa', '>', now())
    ->orderBy('tanggal_kadaluarsa')
    ->get()

$logs->each(function ($log) {
    dump("{$log->kode_identitas} - {$log->jenisLimbah->nama_limbah} - Kadaluarsa: {$log->tanggal_kadaluarsa->format('d M Y')}")
})
```

### Dapatkan log kadaluarsa
```php
\LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')
    ->with(['jenisLimbah', 'user', 'unit'])
    ->orderBy('tanggal_kadaluarsa', 'desc')
    ->get()
```

### Dapatkan user dengan peran tertentu
```php
PenggunaSistem::whereHas('peranPengguna', function ($q) {
    $q->where('nama_peran', 'Operator');
})->get()
```

### Dapatkan log berdasarkan unit
```php
\LogPenyimpananLimbah::where('unit_id', 1)->get()
```

---

## 8. MASS OPERATIONS

### Reset semua log status
```php
\LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')
    ->update(['status_log' => 'Tersimpan'])
```

### Set tanggal kadaluarsa baru
```php
\LogPenyimpananLimbah::where('status_log', 'Tersimpan')
    ->whereNull('tanggal_kadaluarsa')
    ->update(['tanggal_kadaluarsa' => now()->addDays(30)])
```

### Assign user ke unit
```php
PenggunaSistem::whereNull('unit_id')
    ->update(['unit_id' => 1])
```

---

## 9. TESTING AUTHENTICATION

### Login dengan user tertentu
```php
$user = PenggunaSistem::first()
\Auth::login($user)
\Auth::check()
\Auth::id()
```

### Cek user permissions
```php
$user = PenggunaSistem::find(1)
$user->isSuperAdmin()
$user->hasRole('Administrator')
$user->canApproveLogs()
```

---

## 10. STATISTICS

### Statistik Log
```php
$stats = [
    'total' => \LogPenyimpananLimbah::count(),
    'tersimpan' => \LogPenyimpananLimbah::where('status_log', 'Tersimpan')->count(),
    'diangkut' => \LogPenyimpananLimbah::where('status_log', 'Diangkut')->count(),
    'kadaluarsa' => \LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')->count(),
    'near_expiry' => \LogPenyimpananLimbah::where('status_log', 'Tersimpan')
        ->where('tanggal_kadaluarsa', '<=', now()->addDays(7))
        ->count(),
    'expired_last_month' => \LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')
        ->where('tanggal_kadaluarsa', '>=', now()->subMonth())
        ->count(),
]

dump($stats)
```

### Statistik User
```php
$userStats = [
    'total' => PenggunaSistem::count(),
    'active' => PenggunaSistem::where('aktif', true)->count(),
    'inactive' => PenggunaSistem::where('aktif', false)->count(),
    'by_role' => [],
]

PeranPengguna::withCount('penggunaSistem')->get()->each(function ($role) use (&$userStats) {
    $userStats['by_role'][$role->nama_peran] = $role->pengguna_sistem_count;
})

dump($userStats)
```

---

## 11. QUICK ONE-LINERS

```php
// Buat 50 log random
\LogPenyimpananLimbah::factory()->count(50)->create()

// Buat 20 user random
PenggunaSistem::factory()->count(20)->create()

// Hapus semua log (HATI-HATI!)
\LogPenyimpananLimbah::truncate()

// Hapus semua user kecuali super admin
PenggunaSistem::where('user_id', '>', 1)->delete()

// Reset database
\DB::table('migrations')->truncate()
PenggunaSistem::truncate()
\LogPenyimpananLimbah::truncate()

// Tampilkan 10 log terbaru
\LogPenyimpananLimbah::latest()->take(10)->get()->pluck('kode_identitas')

// Cek connection
\DB::connection()->getPdo()
```

---

## 12. HELPER FUNCTIONS

### Cek data kosong
```php
function checkEmpty($model, $name) {
    $count = $model::count();
    echo "{$name}: {$count} record\n";
    return $count === 0;
}

checkEmpty(PenggunaSistem::class, 'Users')
checkEmpty(LogPenyimpananLimbah::class, 'Logs')
checkEmpty(JenisLimbah::class, 'Waste Types')
```

### Buat data dummy cepat
```php
function quickSeed($logs = 10, $users = 5) {
    echo "Membuat {$users} user...\n";
    PenggunaSistem::factory()->count($users)->create();
    echo "Membuat {$logs} log...\n";
    \LogPenyimpananLimbah::factory()->count($logs)->create();
    echo "Selesai!\n";
}

quickSeed(20, 10)
```

### Cek expiry dates
```php
function checkExpiry() {
    $logs = \LogPenyimpananLimbah::where('status_log', 'Tersimpan')->get();
    $today = now();

    foreach ($logs as $log) {
        $days = $log->tanggal_kadaluarsa->diffInDays($today, false);
        echo "{$log->kode_identitas}: ";
        if ($days < 0) {
            echo "SUDAH KADALUARSA ({$days} hari)\n";
        } elseif ($days === 0) {
            echo "KADALUARSA HARI INI!\n";
        } elseif ($days <= 7) {
            echo "AKAN KADALUARSA DALAM {$days} HARI\n";
        } else {
            echo "aman ({$days} hari tersisa)\n";
        }
    }
}

checkExpiry()
```

---

## TIPS

1. Gunakan `q` untuk keluar dari tinker
2. Gunakan `;` di akhir setiap perintah
3. Gunakan `dump($var)` atau `dd($var)` untuk melihat isi variabel
4. Gunakan `->get()` di akhir query
5. Gunakan `->first()` untuk single record
6. Gunakan `::find($id)` untuk mencari by ID

---

## JALANKAN SEEDER LENGKAP

Untuk membuat semua data sekaligus:

```bash
php artisan db:seed --class=DevelopmentSeeder
```

Ini akan membuat:
- 20+ user tambahan
- 30+ jenis limbah
- 10+ kategori
- 10+ perusahaan
- 50+ log penyimpanan (dengan berbagai status)
