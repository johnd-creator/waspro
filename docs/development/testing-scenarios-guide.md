# Testing Scenarios Data Generation Guide

## Overview

This guide explains how to generate specific testing data for WASPRO using the `TestingScenariosSeeder`. The seeder creates data for 7 different testing scenarios, each designed to test specific features of the application.

## Quick Start

```bash
php artisan db:seed --class=TestingScenariosSeeder
```

This single command generates all 7 testing scenarios.

---

## Scenario 1: Approval Workflow Testing

### Purpose
Test the complete approval workflow including pending, approved, and rejected states.

### Data Generated
- **1 Supervisor** user for approving/rejecting
- **3 Operator** users for creating logs
- **30 pending logs** awaiting approval
- **15 approved logs** (approved by Supervisor)
- **10 rejected logs** with rejection reasons

### Test Cases
- ✅ Supervisor can view pending logs
- ✅ Supervisor can approve logs
- ✅ Supervisor can reject logs with reasons
- ✅ Operators can only view their own logs
- ✅ Approved logs show approved_by and approved_at
- ✅ Rejected logs show rejection reasons

### Login Credentials
```
Supervisor: supervisor.approval@test.waspro.com / password
Operator 1: operator.approval1@test.waspro.com / password
Operator 2: operator.approval2@test.waspro.com / password
Operator 3: operator.approval3@test.waspro.com / password
```

### Verification
```php
// In Tinker
$pending = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('approval_status', 'pending')->count();
$approved = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('approval_status', 'approved')->count();
$rejected = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('approval_status', 'rejected')->count();

echo "Pending: {$pending}, Approved: {$approved}, Rejected: {$rejected}\n";
```

---

## Scenario 2: Expiry Notification Testing

### Purpose
Test expiry status tracking and notification system.

### Data Generated
- **20 Critical logs** (1-7 days to expiry)
- **30 Warning logs** (8-30 days to expiry)
- **15 Expired logs** (past expiry date)
- **25 Safe logs** (>30 days to expiry)
- Distributed across **3 units**

### Test Cases
- ✅ Critical logs trigger urgent notifications
- ✅ Warning logs trigger advance notifications
- ✅ Expired logs are highlighted
- ✅ Safe logs don't trigger notifications
- ✅ Expiry status updates correctly
- ✅ Multi-unit expiry tracking works

### Verification
```php
// In Tinker
$critical = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('expiry_status', 'Critical')->count();
$warning = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('expiry_status', 'Warning')->count();
$safe = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('expiry_status', 'Safe')->count();
$expired = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('expiry_status', 'Expired')->count();

echo "Critical: {$critical}, Warning: {$warning}, Safe: {$safe}, Expired: {$expired}\n";
```

---

## Scenario 3: Multi-Unit Access Control Testing

### Purpose
Test role-based access control across multiple units.

### Data Generated
- **1 Super Admin** (global access, no unit restriction)
- **5 Units** with complete user hierarchy
- Per unit:
  - 1 Administrator
  - 1 Supervisor
  - 2 Operators
  - 50 logs
- **Total: 21 users, 250 logs**

### Test Cases
- ✅ Super Admin can access all units
- ✅ Administrator can only access their unit
- ✅ Supervisor can only access their unit
- ✅ Operators can only access their unit
- ✅ Unit scoping works correctly
- ✅ Cross-unit data is isolated

### Login Credentials
```
Super Admin: superadmin.multiunit@test.waspro.com / password
Admin Unit 0: admin.multiunit0@test.waspro.com / password
Admin Unit 1: admin.multiunit1@test.waspro.com / password
... and so on
```

### Verification
```php
// In Tinker
$superAdmin = PenggunaSistem::where('email_address', 'superadmin.multiunit@test.waspro.com')->first();
echo "Super Admin unit_id: " . ($superAdmin->unit_id ?? 'NULL (global)') . "\n";

$units = UnitPembangkit::withCount('logPenyimpananLimbah')->get();
foreach ($units as $unit) {
    echo "{$unit->nama_unit}: {$unit->log_penyimpanan_limbah_count} logs\n";
}
```

---

## Scenario 4: Audit Trail Testing

### Purpose
Test audit logging for create, update, and delete operations.

### Data Generated
- **5 users** with various roles
- **10 create** audit logs
- **10 update** audit logs
- **5 delete** audit logs
- All with user_id, old_value, new_value, ip_address, user_agent

### Test Cases
- ✅ Create operations are logged
- ✅ Update operations show old and new values
- ✅ Delete operations show deleted data
- ✅ User information is captured
- ✅ IP address and user agent are logged
- ✅ Audit trail is searchable

### Verification
```php
// In Tinker
$creates = AuditLog::where('action', 'create')->count();
$updates = AuditLog::where('action', 'update')->count();
$deletes = AuditLog::where('action', 'delete')->count();

echo "Creates: {$creates}, Updates: {$updates}, Deletes: {$deletes}\n";

// Check a sample audit log
$audit = AuditLog::where('action', 'update')->first();
echo "Old value: " . json_encode($audit->old_value) . "\n";
echo "New value: " . json_encode($audit->new_value) . "\n";
```

---

## Scenario 5: Document Management Testing

### Purpose
Test document upload and management features.

### Data Generated
- **20 logs with documents**:
  - dokumen_path filled
  - dokumen_original_name filled
  - dokumen_mime = 'application/pdf'
  - dokumen_size (80KB - 5MB)
  - dokumen_uploaded_at filled
- **10 logs without documents** (all document fields NULL)

### Test Cases
- ✅ Document upload works
- ✅ Document metadata is stored
- ✅ Document size validation works
- ✅ Document download works
- ✅ Logs without documents are handled
- ✅ Document deletion works

### Verification
```php
// In Tinker
$withDocs = LogPenyimpananLimbah::withoutGlobalScopes()
    ->whereNotNull('dokumen_path')->count();
$withoutDocs = LogPenyimpananLimbah::withoutGlobalScopes()
    ->whereNull('dokumen_path')->count();

echo "With documents: {$withDocs}, Without documents: {$withoutDocs}\n";

// Check document sizes
$log = LogPenyimpananLimbah::withoutGlobalScopes()
    ->whereNotNull('dokumen_path')->first();
echo "Sample document: {$log->dokumen_original_name}\n";
echo "Size: " . number_format($log->dokumen_size / 1024, 2) . " KB\n";
```

---

## Scenario 6: Cost Tracking Testing

### Purpose
Test cost calculation and tracking for waste transportation.

### Data Generated
- **5 High-cost** jenis limbah (>100k/kg)
- **5 Medium-cost** jenis limbah (80k-100k/kg)
- **5 Low-cost** jenis limbah (<80k/kg)
- **50 logs** using these jenis limbah

### Test Cases
- ✅ Cost calculation is accurate
- ✅ High-cost waste is flagged
- ✅ Cost reports are correct
- ✅ Cost filtering works
- ✅ Total cost calculation works
- ✅ Cost per unit calculation works

### Verification
```php
// In Tinker
$highCost = JenisLimbah::where('biaya_pengangkutan_per_kg', '>', 100000)->count();
$mediumCost = JenisLimbah::whereBetween('biaya_pengangkutan_per_kg', [80000, 100000])->count();
$lowCost = JenisLimbah::where('biaya_pengangkutan_per_kg', '<', 80000)->count();

echo "High: {$highCost}, Medium: {$mediumCost}, Low: {$lowCost}\n";

// Calculate total cost for a sample log
$log = LogPenyimpananLimbah::withoutGlobalScopes()->with('jenisLimbah')->first();
if ($log && $log->jenisLimbah) {
    $totalCost = $log->jumlah_limbah_masuk * $log->jenisLimbah->biaya_pengangkutan_per_kg;
    echo "Sample log cost: Rp " . number_format($totalCost, 2) . "\n";
}
```

---

## Scenario 7: Bulk Operations Testing

### Purpose
Test bulk approve, reject, and delete operations.

### Data Generated
- **10 users** (5 Supervisors, 5 Administrators)
- **100 pending logs** (20 per unit across 5 units)
- All logs ready for bulk operations

### Test Cases
- ✅ Bulk approve works
- ✅ Bulk reject works
- ✅ Bulk delete works
- ✅ Unit scoping in bulk operations
- ✅ Permission checks in bulk operations
- ✅ Performance with large datasets

### Login Credentials
```
Supervisor Unit 0: supervisor.bulk0@test.waspro.com / password
Administrator Unit 0: admin.bulk0@test.waspro.com / password
... and so on for units 1-4
```

### Verification
```php
// In Tinker
$pendingLogs = LogPenyimpananLimbah::withoutGlobalScopes()
    ->where('approval_status', 'pending')->count();

echo "Pending logs ready for bulk operations: {$pendingLogs}\n";

// Check distribution per unit
$units = UnitPembangkit::take(5)->get();
foreach ($units as $unit) {
    $count = LogPenyimpananLimbah::withoutGlobalScopes()
        ->where('unit_id', $unit->unit_id)
        ->where('approval_status', 'pending')
        ->count();
    echo "{$unit->nama_unit}: {$count} pending logs\n";
}
```

---

## Running Individual Scenarios

If you want to run scenarios individually, you can modify the seeder or use Tinker:

```php
// In Tinker
$seeder = new \Database\Seeders\TestingScenariosSeeder();
$seeder->setCommand(new \Illuminate\Console\Command());

// Run specific scenario
DB::beginTransaction();
try {
    // Example: Run only Scenario 1
    $seeder->scenario1_ApprovalWorkflow();
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage();
}
```

---

## Data Cleanup

To remove testing data (be careful!):

```php
// In Tinker - CAUTION: This deletes data!

// Delete test users
PenggunaSistem::where('email_address', 'LIKE', '%@test.waspro.com')->delete();

// Delete logs created during testing
// (Add specific criteria based on your needs)
```

---

## Summary Table

| Scenario | Users | Logs | Units | Special Features |
|----------|-------|------|-------|------------------|
| 1. Approval Workflow | 4 | 55 | 1 | Pending/Approved/Rejected states |
| 2. Expiry Notification | 0 | 90 | 3 | Critical/Warning/Safe/Expired |
| 3. Multi-Unit Access | 21 | 250 | 5 | Super Admin + unit hierarchy |
| 4. Audit Trail | 5 | 0 | 1 | 25 audit logs (create/update/delete) |
| 5. Document Management | 0 | 30 | - | 20 with docs, 10 without |
| 6. Cost Tracking | 0 | 50 | - | 15 jenis limbah (varied costs) |
| 7. Bulk Operations | 10 | 100 | 5 | All pending for bulk ops |
| **TOTAL** | **40** | **575** | **5+** | **25 audit logs, 15 jenis limbah** |

---

## Best Practices

1. **Run in Development Only**: These seeders are for testing, not production
2. **Use Test Email Domain**: All test users use `@test.waspro.com`
3. **Clean Up After Testing**: Remove test data when done
4. **Verify Data**: Always verify data was created correctly
5. **Use Transactions**: The seeder uses transactions for data consistency

---

## Troubleshooting

### Seeder Fails

1. Check migrations are up to date:
   ```bash
   php artisan migrate:status
   ```

2. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. Check for existing test users:
   ```php
   PenggunaSistem::where('email_address', 'LIKE', '%@test.waspro.com')->count()
   ```

### Foreign Key Errors

The seeder automatically creates required dependencies (units, roles, etc.). If you get foreign key errors:

1. Ensure roles exist:
   ```bash
   php artisan db:seed --class=PeranPenggunaSeeder
   ```

2. Ensure units exist:
   ```bash
   php artisan db:seed --class=UnitPembangkitSeeder
   ```

### Duplicate Email Errors

If test users already exist, either:
1. Delete them first
2. Modify the seeder to use different email addresses
3. Use `updateOrCreate` instead of `create`

---

## Next Steps

After generating test data:

1. **Test Each Scenario**: Go through each test case
2. **Verify Functionality**: Ensure features work as expected
3. **Document Issues**: Note any bugs or unexpected behavior
4. **Clean Up**: Remove test data when done
5. **Iterate**: Regenerate data as needed for testing

---

## Files

- **Seeder**: [TestingScenariosSeeder.php](file:///home/john-d/Documents/waspro/database/seeders/TestingScenariosSeeder.php)
- **This Guide**: [testing-scenarios-guide.md](file:///home/john-d/Documents/waspro/docs/development/testing-scenarios-guide.md)
