# Performance Testing Data Generation Guide

## Overview

This guide explains how to generate bulk data for performance testing in WASPRO using the `PerformanceTestingSeeder`. The seeder creates production-scale data with efficient batch processing and progress feedback.

## Quick Start

```bash
php artisan db:seed --class=PerformanceTestingSeeder
```

**Estimated Time**: 5-15 minutes depending on your system

---

## Data Generated

### Summary

| Resource | Target Count | Distribution |
|----------|--------------|--------------|
| Units | 50 | Across major Indonesian cities |
| Users | 200 | 1 Super Admin, 50 Admins, 50 Supervisors, 99 Operators |
| Jenis Limbah | 50 | 40 active, 10 inactive with realistic costs |
| Logs | 10,000 | Distributed across units with realistic dates |
| Karakteristik | 10 | Supporting data |
| Kategori | 20 | Supporting data |
| Perusahaan | 50 | Supporting data |

### Detailed Breakdown

#### 1. Units (50)
- Distributed across major Indonesian cities
- Jakarta, Surabaya, Bandung, Medan, Semarang, etc.
- Capacity: 200-600 MW per unit

#### 2. Users (200)
- **1 Super Admin**: Global access (no unit restriction)
  - Email: `superadmin.perf@waspro.com`
  - Password: `password`

- **50 Administrators**: 1 per unit
  - Email pattern: `admin.perf0@waspro.com` through `admin.perf49@waspro.com`
  - Password: `password`

- **50 Supervisors**: 1 per unit
  - Email pattern: `supervisor.perf0@waspro.com` through `supervisor.perf49@waspro.com`
  - Password: `password`

- **99 Operators**: 2 per unit (first 49 units), 1 for last unit
  - Email pattern: `operator.perf1@waspro.com` through `operator.perf99@waspro.com`
  - Password: `password`

#### 3. Jenis Limbah (50)
- **40 active** (status_aktif = true)
- **10 inactive** (status_aktif = false)

**Cost Distribution**:
- 10 Very High Cost: 120k-180k/kg
- 15 High Cost: 80k-120k/kg
- 15 Medium Cost: 50k-80k/kg
- 10 Low Cost: 30k-50k/kg

#### 4. Log Penyimpanan Limbah (10,000)

**Status Log Distribution**:
- 5,000 Tersimpan (50%)
- 3,500 Diangkut (35%)
- 1,500 Expired (15%)

**Approval Status Distribution**:
- 5,000 Pending (50%)
- 3,000 Approved (30%)
- 2,000 Rejected (20%)

**Expiry Status Distribution**:
- Critical: 1-7 days to expiry
- Warning: 8-30 days to expiry
- Safe: >30 days to expiry
- Expired: Past expiry date

**Unit Distribution**:
- ~200 logs per unit (average)
- Distributed across all 50 units

---

## Features

### 1. Batch Processing
- Users created in batches of 50-100
- Logs created in batches of 500
- Transaction per batch for data integrity
- Memory cleanup between batches

### 2. Progress Feedback
- Real-time progress bars
- Percentage completion
- Current/Total counts
- Step-by-step execution

### 3. Memory Optimization
- Query logging disabled
- Garbage collection between batches
- Efficient chunk processing
- Peak memory usage tracking

### 4. Execution Time Tracking
- Start time recorded
- End time calculated
- Total execution time displayed
- Performance metrics shown

---

## Execution Steps

The seeder runs in 6 steps:

### Step 1: Supporting Data
- Creates 50 units
- Creates 10 karakteristik limbah
- Creates 20 kategori kegiatan
- Creates 50 perusahaan penghasil

### Step 2: Jenis Limbah
- Creates 50 jenis limbah
- 40 active, 10 inactive
- Realistic cost distribution

### Step 3: Bulk Users
- Creates 1 Super Admin
- Creates 50 Administrators
- Creates 50 Supervisors
- Creates 99 Operators

### Step 4: Bulk Logs (Longest Step)
- Creates 10,000 logs in batches
- 500 logs per batch (20 batches)
- Progress bar shows completion
- Memory cleanup between batches

### Step 5: Update Approval Statuses
- Updates all logs with approval statuses
- 50% pending, 30% approved, 20% rejected
- Assigns approvers (Supervisors/Admins)
- Adds rejection reasons where applicable

### Step 6: Final Summary
- Shows total data generated
- Displays approval status distribution
- Shows expiry status distribution
- Reports peak memory usage

---

## Performance Metrics

### Expected Performance

| Metric | Value |
|--------|-------|
| Execution Time | 5-15 minutes |
| Peak Memory | 256-512 MB |
| Batch Size | 500 logs |
| Total Batches | 20 |

### Optimization Techniques

1. **Query Log Disabled**: Reduces memory overhead
2. **Batch Transactions**: Ensures data integrity
3. **Garbage Collection**: Clears memory between batches
4. **Chunk Processing**: Handles large datasets efficiently
5. **Progress Feedback**: Shows real-time status

---

## Verification

After running the seeder, verify the data:

```php
// In Tinker
echo "Units: " . UnitPembangkit::count() . "\n";
echo "Users: " . PenggunaSistem::count() . "\n";
echo "Jenis Limbah: " . JenisLimbah::count() . "\n";
echo "Logs: " . LogPenyimpananLimbah::withoutGlobalScopes()->count() . "\n";

// Check distributions
$pending = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'pending')->count();
$approved = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'approved')->count();
$rejected = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'rejected')->count();

echo "\nApproval Distribution:\n";
echo "Pending: {$pending} (" . round($pending/10000*100, 1) . "%)\n";
echo "Approved: {$approved} (" . round($approved/10000*100, 1) . "%)\n";
echo "Rejected: {$rejected} (" . round($rejected/10000*100, 1) . "%)\n";

// Check per-unit distribution
$units = UnitPembangkit::withCount('logPenyimpananLimbah')->take(5)->get();
foreach ($units as $unit) {
    echo "{$unit->nama_unit}: {$unit->log_penyimpanan_limbah_count} logs\n";
}
```

---

## Performance Testing Use Cases

### 1. Load Testing
- Test application with 10,000+ records
- Measure query performance
- Test pagination with large datasets
- Verify indexing effectiveness

### 2. Multi-Unit Scalability
- Test with 50 units
- Verify unit scoping works at scale
- Test cross-unit queries
- Measure performance degradation

### 3. User Concurrency
- Test with 200 concurrent users
- Verify role-based access at scale
- Test permission checks performance
- Measure authentication overhead

### 4. Reporting Performance
- Generate reports with 10,000 records
- Test filtering and sorting
- Measure export performance
- Verify aggregation queries

### 5. Approval Workflow
- Test bulk approval operations
- Measure approval query performance
- Test notification system at scale
- Verify workflow integrity

---

## Troubleshooting

### Memory Limit Errors

If you encounter memory limit errors:

```bash
php -d memory_limit=512M artisan db:seed --class=PerformanceTestingSeeder
```

Or increase in `php.ini`:
```ini
memory_limit = 512M
```

### Timeout Errors

If execution times out:

```bash
php -d max_execution_time=600 artisan db:seed --class=PerformanceTestingSeeder
```

Or increase in `php.ini`:
```ini
max_execution_time = 600
```

### Slow Performance

If seeder runs slowly:

1. **Check database indexes**:
   ```sql
   SHOW INDEX FROM log_penyimpanan_limbah;
   ```

2. **Disable foreign key checks temporarily** (use with caution):
   ```php
   DB::statement('SET FOREIGN_KEY_CHECKS=0;');
   // Run seeder
   DB::statement('SET FOREIGN_KEY_CHECKS=1;');
   ```

3. **Use SSD storage**: Significantly faster than HDD

4. **Increase batch size**: Modify `$batchSize` in seeder (be careful with memory)

### Partial Completion

If seeder fails mid-execution:

1. **Check what was created**:
   ```php
   echo "Logs created: " . LogPenyimpananLimbah::withoutGlobalScopes()->count() . "\n";
   ```

2. **Resume from checkpoint**: The seeder checks existing data and only creates what's missing

3. **Clean up and restart**:
   ```php
   // Delete performance testing data
   PenggunaSistem::where('email_address', 'LIKE', '%.perf@waspro.com')->delete();
   ```

---

## Cleanup

To remove performance testing data:

```php
// In Tinker - CAUTION: This deletes data!

DB::beginTransaction();

try {
    // Delete performance test users
    $deleted = PenggunaSistem::where('email_address', 'LIKE', '%.perf@waspro.com')->delete();
    echo "Deleted {$deleted} performance test users\n";
    
    // Logs will be cascade deleted if foreign keys are set up
    
    DB::commit();
    echo "Cleanup complete\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
```

---

## Best Practices

1. ✅ **Run in Development/Staging**: Not in production
2. ✅ **Backup First**: Always backup before bulk operations
3. ✅ **Monitor Resources**: Watch memory and CPU usage
4. ✅ **Test Incrementally**: Start with smaller datasets
5. ✅ **Verify Data**: Always check data after generation
6. ✅ **Clean Up**: Remove test data when done

---

## Comparison with Other Seeders

| Seeder | Users | Logs | Purpose |
|--------|-------|------|---------|
| QuickDevDataSeeder | 50 | 200 | Development |
| TestingScenariosSeeder | 40 | 575 | Feature Testing |
| **PerformanceTestingSeeder** | **200** | **10,000** | **Performance Testing** |

---

## Next Steps

After generating performance data:

1. **Run Performance Tests**
   - Measure page load times
   - Test query performance
   - Check memory usage
   - Verify scalability

2. **Optimize Queries**
   - Add indexes where needed
   - Optimize N+1 queries
   - Use eager loading
   - Cache frequently accessed data

3. **Test Features**
   - Bulk operations
   - Reporting
   - Filtering and sorting
   - Export functionality

4. **Monitor Production**
   - Set up monitoring
   - Track performance metrics
   - Plan for scaling
   - Optimize infrastructure

---

## Files

- **Seeder**: [PerformanceTestingSeeder.php](file:///home/john-d/Documents/waspro/database/seeders/PerformanceTestingSeeder.php)
- **This Guide**: [performance-testing-guide.md](file:///home/john-d/Documents/waspro/docs/development/performance-testing-guide.md)

---

## Summary

✅ **50 units** for multi-unit testing  
✅ **200 users** with proper role distribution  
✅ **50 jenis limbah** with realistic costs  
✅ **10,000 logs** with varied statuses  
✅ **Batch processing** for efficiency  
✅ **Progress feedback** for monitoring  
✅ **Memory optimization** for large datasets  
✅ **Production-scale data** for realistic testing  

Ready for performance testing! 🚀
