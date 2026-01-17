# Factory Usage Guide - WASPRO

## Factories Baru yang Dibuat

Berikut adalah 4 factory baru yang telah dibuat untuk sistem WASPRO:

### 1. ApprovalLogFactory

Factory untuk testing approval workflow pada log penyimpanan limbah.

**Lokasi**: `database/factories/ApprovalLogFactory.php`

**States yang Tersedia**:
- `approved()` - Membuat approval log dengan status approved
- `rejected()` - Membuat approval log dengan status rejected beserta alasan

**Contoh Penggunaan**:
```php
// Membuat approval log yang disetujui
$approval = ApprovalLog::factory()->approved()->create();

// Membuat approval log yang ditolak
$rejected = ApprovalLog::factory()->rejected()->create();

// Membuat approval log random (approve atau reject)
$random = ApprovalLog::factory()->create();

// Membuat 10 approval logs
ApprovalLog::factory()->count(10)->create();

// Membuat approval log dengan data spesifik
ApprovalLog::factory()->create([
    'log_id' => 1,
    'approved_by' => 2,
]);
```

---

### 2. AuditLogFactory

Factory untuk testing audit trail sistem.

**Lokasi**: `database/factories/AuditLogFactory.php`

**States yang Tersedia**:
- `createAction()` - Audit log untuk action create
- `update()` - Audit log untuk action update
- `delete()` - Audit log untuk action delete
- `forTable(string $tableName)` - Audit log untuk tabel tertentu

**Contoh Penggunaan**:
```php
// Membuat audit log untuk create action
$create = AuditLog::factory()->createAction()->create();

// Membuat audit log untuk update action
$update = AuditLog::factory()->update()->create();

// Membuat audit log untuk delete action
$delete = AuditLog::factory()->delete()->create();

// Membuat audit log untuk tabel spesifik
$audit = AuditLog::factory()
    ->forTable('log_penyimpanan_limbah')
    ->update()
    ->create();

// Membuat 50 audit logs random
AuditLog::factory()->count(50)->create();
```

---

### 3. PeranPenggunaFactory

Factory untuk testing role-based access control.

**Lokasi**: `database/factories/PeranPenggunaFactory.php`

**States yang Tersedia**:
- `active()` - Role yang aktif
- `inactive()` - Role yang tidak aktif
- `administrator()` - Role Administrator
- `operator()` - Role Operator
- `supervisor()` - Role Supervisor
- `viewer()` - Role Viewer

**Contoh Penggunaan**:
```php
// Membuat role Administrator
$admin = PeranPengguna::factory()->administrator()->create();

// Membuat role Operator
$operator = PeranPengguna::factory()->operator()->create();

// Membuat role Supervisor yang tidak aktif
$supervisor = PeranPengguna::factory()->supervisor()->inactive()->create();

// Membuat role Viewer
$viewer = PeranPengguna::factory()->viewer()->create();

// Membuat role random
$random = PeranPengguna::factory()->create();

// Membuat semua role
$roles = [
    PeranPengguna::factory()->administrator()->create(),
    PeranPengguna::factory()->operator()->create(),
    PeranPengguna::factory()->supervisor()->create(),
    PeranPengguna::factory()->viewer()->create(),
];
```

---

### 4. ApplicationSettingFactory

Factory untuk testing konfigurasi aplikasi.

**Lokasi**: `database/factories/ApplicationSettingFactory.php`

**States yang Tersedia**:

**Type States**:
- `string()` - Setting dengan tipe string
- `integer()` - Setting dengan tipe integer
- `boolean()` - Setting dengan tipe boolean
- `json()` - Setting dengan tipe JSON

**Category States**:
- `general()` - Kategori general
- `limbah()` - Kategori limbah
- `notification()` - Kategori notification
- `system()` - Kategori system

**Status States**:
- `active()` - Setting aktif
- `inactive()` - Setting tidak aktif

**Contoh Penggunaan**:
```php
// Membuat setting integer untuk kategori limbah
$setting = ApplicationSetting::factory()
    ->integer()
    ->limbah()
    ->create();

// Membuat setting boolean untuk kategori system
$maintenance = ApplicationSetting::factory()
    ->boolean()
    ->system()
    ->create([
        'key' => 'maintenance_mode',
        'description' => 'Mode maintenance aplikasi',
    ]);

// Membuat setting JSON untuk notification
$notifConfig = ApplicationSetting::factory()
    ->json()
    ->notification()
    ->create();

// Membuat setting string yang tidak aktif
$inactive = ApplicationSetting::factory()
    ->string()
    ->general()
    ->inactive()
    ->create();

// Membuat 20 settings random
ApplicationSetting::factory()->count(20)->create();
```

---

## Kombinasi dengan Factory Lain

Semua factory ini dapat dikombinasikan dengan factory yang sudah ada:

```php
// Membuat log limbah dengan approval
$log = LogPenyimpananLimbah::factory()->create();
$approval = ApprovalLog::factory()->approved()->create([
    'log_id' => $log->log_id,
]);

// Membuat user dengan role
$user = PenggunaSistem::factory()->create();
$role = PeranPengguna::factory()->administrator()->create();
$user->peranPengguna()->attach($role->peran_id);

// Membuat audit log untuk user tertentu
$audit = AuditLog::factory()
    ->update()
    ->forTable('pengguna_sistem')
    ->create([
        'user_id' => $user->user_id,
        'record_id' => $user->user_id,
    ]);
```

---

## Seeder Example

Contoh penggunaan dalam seeder:

```php
<?php

namespace Database\Seeders;

use App\Models\ApprovalLog;
use App\Models\ApplicationSetting;
use App\Models\AuditLog;
use App\Models\PeranPengguna;
use Illuminate\Database\Seeder;

class DevelopmentDataSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        PeranPengguna::factory()->administrator()->create();
        PeranPengguna::factory()->operator()->create();
        PeranPengguna::factory()->supervisor()->create();
        PeranPengguna::factory()->viewer()->create();

        // Buat application settings
        ApplicationSetting::factory()->count(10)->create();

        // Buat approval logs
        ApprovalLog::factory()->approved()->count(50)->create();
        ApprovalLog::factory()->rejected()->count(10)->create();

        // Buat audit logs
        AuditLog::factory()->createAction()->count(30)->create();
        AuditLog::factory()->update()->count(100)->create();
        AuditLog::factory()->delete()->count(20)->create();
    }
}
```

---

## Testing Example

Contoh penggunaan dalam testing:

```php
<?php

namespace Tests\Feature;

use App\Models\ApprovalLog;
use App\Models\AuditLog;
use App\Models\PeranPengguna;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    public function test_can_approve_log()
    {
        $approval = ApprovalLog::factory()->approved()->create();
        
        $this->assertEquals('approve', $approval->action);
        $this->assertNull($approval->rejected_reason);
    }

    public function test_can_reject_log_with_reason()
    {
        $approval = ApprovalLog::factory()->rejected()->create();
        
        $this->assertEquals('reject', $approval->action);
        $this->assertNotNull($approval->rejected_reason);
    }

    public function test_audit_log_tracks_changes()
    {
        $audit = AuditLog::factory()->update()->create();
        
        $this->assertEquals('update', $audit->action);
        $this->assertNotNull($audit->old_value);
        $this->assertNotNull($audit->new_value);
    }

    public function test_role_can_be_activated()
    {
        $role = PeranPengguna::factory()->inactive()->create();
        
        $this->assertFalse($role->is_active);
        
        $role->toggleStatus();
        
        $this->assertTrue($role->is_active);
    }
}
```

---

## Model Updates

Trait `HasFactory` telah ditambahkan ke models berikut:
- ✅ `ApprovalLog` (sudah ada sebelumnya)
- ✅ `AuditLog` (baru ditambahkan)
- ✅ `PeranPengguna` (sudah ada sebelumnya)
- ✅ `ApplicationSetting` (baru ditambahkan)

---

## Catatan Penting

1. **Foreign Key Relationships**: Semua factory sudah menghandle foreign key relationships dengan baik. Factory akan otomatis membuat data terkait jika belum ada.

2. **Indonesian Fake Data**: Data yang user-facing menggunakan bahasa Indonesia untuk lebih realistis.

3. **Factory States**: Gunakan factory states untuk berbagai scenarios testing (approved, rejected, create, update, delete, dll).

4. **Naming Convention**: Ikuti pattern `Model::factory()->stateName()->create()`.

5. **Database Constraints**: Factory sudah disesuaikan dengan constraint database (contoh: enum 'approve'/'reject' untuk ApprovalLog).

---

## Troubleshooting

Jika mengalami error saat menggunakan factory:

1. **Foreign Key Error**: Pastikan data parent sudah ada atau biarkan factory membuatnya otomatis
2. **Enum Error**: Pastikan menggunakan state yang sesuai dengan enum di database
3. **Unique Constraint**: Gunakan `unique()` pada faker atau override attribute yang harus unique

Contoh:
```php
// Jika key harus unique
ApplicationSetting::factory()->create([
    'key' => 'unique_key_' . uniqid(),
]);
```
