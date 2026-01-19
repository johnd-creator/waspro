# WASPRO - Waste Management System

> **AI Agent Guidelines for WASPRO Application Development**

---

## 📋 PROJECT OVERVIEW

**Project Name:** WASPRO (Waste Management Professional)
**Type:** Waste Management System for Industrial & Medical Waste
**Framework:** Laravel 12 (PHP 8.2+)
**Database:** MariaDB 10.11
**Frontend:** Blade Templates + Tailwind CSS
**Authentication:** Custom JWT-like token system (offline mode support)

---

## 🏗️ ARCHITECTURE

### **Project Structure**
```
waspro/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # HTTP Controllers
│   │   ├── Middleware/           # Custom Middleware
│   │   └── Requests/             # Form Requests
│   ├── Models/                   # Eloquent Models
│   ├── Services/                 # Business Logic Layer
│   ├── Helpers/                  # Utility Functions
│   └── Console/                 # Console Commands
├── database/
│   ├── migrations/               # Database Migrations
│   └── seeders/                  # Database Seeders
├── resources/
│   ├── views/                    # Blade Templates
│   └── assets/                   # CSS/JS assets
├── config/                       # Configuration Files
├── routes/
│   ├── web.php                   # Web Routes
│   └── api.php                   # API Routes (Offline Mode)
└── tests/                        # PHPUnit Tests
```

### **Key Architectural Patterns**

1. **Service Layer Pattern**: Business logic in `app/Services/`
2. **Repository Pattern**: Data access through Models (not repositories)
3. **Middleware**: Custom authentication and authorization
4. **Offline Mode**: API endpoints support offline synchronization
5. **Audit Logging**: Comprehensive audit trail for all operations

---

## 🗄️ DATABASE CONFIGURATION

### **Connection Details**
- **Driver:** MariaDB
- **Host:** 127.0.0.1
- **Port:** 3306
- **Database:** waspro
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci
- **Engine:** InnoDB (explicitly set)

### **Performance Optimizations**

**Indexes Added (2026-01-20):**
- `idx_unit_expiry_date` (unit_id, expiry_status, tanggal_kadaluarsa)
- `idx_unit_status_date` (unit_id, status_log, tanggal_limbah_masuk)
- `idx_status_pengangkutan` (status_log, tanggal_pengangkutan)
- `idx_client_uuid` (client_uuid)
- `idx_identitas_status` (kode_identitas, status_log)
- `idx_status_log_lower` (status_log_lower) - GENERATED COLUMN
- `idx_unit_active_email` (unit_id, aktif, email_address)
- `idx_user_created_action` (user_id, created_at, action)
- `idx_table_record_created` (table_name, record_id, created_at)

**Query Optimization Rules:**
1. Always use indexes - check with `EXPLAIN`
2. Avoid `LOWER()` in WHERE clauses - use generated columns
3. Use `selectRaw()` for aggregations, not multiple `count()` calls
4. Prefer `joins` over `whereHas()` for performance
5. Use `with()` for eager loading to avoid N+1 queries
6. Use bulk updates (`updateWhereIn()`) instead of loops

---

## 📝 CODE CONVENTIONS

### **PHP/Laravel Standards**

1. **PSR-12 Coding Standard**
   - 4 spaces indentation (NO tabs)
   - Max line length: 120 characters
   - Use type hints where possible

2. **Naming Conventions**
   ```php
   // Models: PascalCase, singular
   LogPenyimpananLimbah

   // Controllers: PascalCase, singular, ending with "Controller"
   DashboardController

   // Services: PascalCase, singular, ending with "Service"
   DashboardService

   // Methods: camelCase, descriptive verbs
   public function getDashboardData(array $filters): array

   // Variables: camelCase
   $recentLogs = [];

   // Database tables: snake_case, plural
   log_penyimpanan_limbah

   // Database columns: snake_case
   tanggal_limbah_masuk
   ```

3. **Service Layer Pattern**
   ```php
   // GOOD: Business logic in Service
   class DashboardService
   {
       public function getDashboardData(array $filters): array
       {
           return [
               'statistics' => $this->getStatistics($filters),
               'charts' => $this->getChartData($filters),
           ];
       }
   }

   // AVOID: Business logic in Controller
   class DashboardController
   {
       // Keep controllers thin - just call services
   }
   ```

4. **Eloquent Best Practices**
   ```php
   // GOOD: Eager loading
   $logs = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil'])->get();

   // AVOID: N+1 query
   $logs = LogPenyimpananLimbah::get();
   foreach ($logs as $log) {
       $name = $log->jenisLimbah->nama_limbah; // N+1!
   }

   // GOOD: Bulk operations
   LogPenyimpananLimbah::whereIn('log_id', $ids)->update([...]);

   // AVOID: Loop updates
   foreach ($ids as $id) {
       LogPenyimpananLimbah::find($id)->update([...]);
   }

   // GOOD: Single aggregation query
   $stats = DB::table('logs')
       ->selectRaw('
           COUNT(*) as total,
           SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active
       ')
       ->first();

   // AVOID: Multiple count queries
   $total = DB::table('logs')->count();
   $active = DB::table('logs')->where('status', 'active')->count();
   ```

### **Blade Template Standards**

1. **Use array access for Eloquent collections with `toArray()`**
   ```blade
   <!-- GOOD: When data is toArray() -->
   {{ $log['nama_limbah'] }}
   {{ $log['perusahaan_penghasil']['nama_perusahaan'] }}

   <!-- GOOD: When data is Eloquent object -->
   {{ $log->namaLimbah->nama_limbah }}
   ```

2. **Use helper functions for dates**
   ```blade
   <!-- GOOD -->
   {{ \Carbon\Carbon::parse($log['created_at'])->format('d/m/Y') }}

   <!-- AVOID -->
   {{ $log->created_at->format('d/m/Y') }} // Only for Eloquent objects
   ```

3. **Route parameters must include key name**
   ```blade
   <!-- GOOD -->
   <a href="{{ route('log-penyimpanan.show', ['log_penyimpanan' => $log['log_id']]) }}">

   <!-- AVOID -->
   <a href="{{ route('log-penyimpanan.show', $log) }}"> <!-- Missing key! -->
   ```

---

## 🔐 AUTHENTICATION & AUTHORIZATION

### **Authentication System**
- Custom JWT-like token for offline mode
- Standard Laravel sessions for online mode
- Token stored in localStorage
- Token includes: user_id, unit_id, role, expiry

### **Authorization Levels**
1. **Super Admin**: Full access to all units
2. **Admin**: Full access within assigned unit
3. **Supervisor**: Can approve/reject logs
4. **Operator**: Can create and update logs

### **Middleware**
```php
// Auth::guard('web')->user() returns PenggunaSistem model
$user->isSuperAdmin();     // Check if super admin
$user->canApproveLogs();    // Check if can approve
$user->unit_id;             // Get assigned unit
```

---

## 📊 KEY MODELS & RELATIONSHIPS

### **Core Models**

1. **LogPenyimpananLimbah**
   - Primary waste log record
   - Relationships:
     - `jenisLimbah()` → JenisLimbah
     - `perusahaanPenghasil()` → PerusahaanPenghasil
     - `unitPembangkit()` → UnitPembangkit
     - `penggunaSistem()` → PenggunaSistem

2. **PenggunaSistem**
   - User model with custom authentication
   - Methods: `isSuperAdmin()`, `canApproveLogs()`

3. **JenisLimbah**
   - Waste type definition
   - Key field: `waktu_penyimpanan_hari` (for expiry calculation)

### **Important Model Methods**

```php
// LogPenyimpananLimbah
$log->updateExpiryStatus();  // Updates expiry_status based on dates
$log->getDaysUntilExpiry();  // Returns days until expiry

// JenisLimbah
$jenis->nama_limbah;         // Waste type name
$jenis->waktu_penyimpanan_hari; // Storage duration in days
```

---

## 🚀 PERFORMANCE GUIDELINES

### **Dashboard Optimization**
- Single aggregation query for statistics (not 5 separate counts)
- Cache dashboard data with TTL (300 seconds)
- Use selective eager loading with `with(['relation:col1,col2'])`
- Limit recent logs to 10 in lite mode, 20 in normal mode

### **Bulk Operations**
- Always use `whereIn()` with `update()` instead of loops
- Example:
  ```php
  // 1 query for N records
  LogPenyimpananLimbah::whereIn('log_id', $logIds)->update([...]);
  ```

### **Cache Strategy**
- File cache driver (can be switched to Redis)
- Cache keys include unit suffix for proper scoping
- Clear cache on relevant data changes
- Dashboard cache TTL: 300 seconds (5 minutes)

### **Query Optimization Checklist**
- [ ] Use `EXPLAIN` on slow queries
- [ ] Check indexes are being used
- [ ] Avoid `LOWER()` in WHERE clauses
- [ ] Use `select()` to limit columns
- [ ] Eager load relationships
- [ ] Use bulk operations instead of loops
- [ ] Cache expensive computations

---

## 🧪 TESTING GUIDELINES

### **Manual Testing**
- Use Tinker for quick tests:
  ```bash
  php artisan tinker --execute="App\Models\LogPenyimpananLimbah::count();"
  ```

### **Testing Commands**
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Run migrations
php artisan migrate --force
php artisan migrate:status

# Check performance
php artisan tinker --execute="DB::enableQueryLog(); // run query; Log::debug(DB::getQueryLog());"
```

### **Sample Data Creation**
```bash
# Create sample logs
php artisan tinker --execute="include 'tinker_create_sample_logs.php';"
php artisan tinker --execute="include 'tinker_create_additional_logs.php';"
```

---

## 📁 IMPORTANT FILES

### **Configuration**
- `config/database.php` - Database connections
- `config/queue.php` - Queue configuration
- `config/cache.php` - Cache configuration
- `.env` - Environment variables

### **Services (Business Logic)**
- `app/Services/DashboardService.php` - Dashboard data aggregation
- `app/Services/LogPenyimpananService.php` - Log CRUD and filtering
- `app/Services/PerusahaanPenghasilService.php` - Company management

### **Key Controllers**
- `app/Http/Controllers/DashboardController.php` - Dashboard pages
- `app/Http/Controllers/LogPenyimpananLimbahController.php` - Log CRUD
- `app/Http/Controllers/PengangkutanLimbahController.php` - Waste transport
- `app/Http/Controllers/ReportController.php` - Reporting

### **API Controllers (Offline Mode)**
- `app/Http/Controllers/Api/LogPenyimpananController.php` - Sync API

---

## 🔄 OFFLINE MODE

### **How It Works**
1. API endpoints provide data for offline use
2. Client stores data locally (localStorage/IndexedDB)
3. Client generates `client_uuid` for new records
4. On sync: Client POSTs to `/api/log-penyimpanan/sync`
5. Server checks `client_uuid` to prevent duplicates

### **Important Fields**
- `client_uuid` - Unique identifier from client (indexed)
- `created_at_client` - Timestamp when created on client
- `updated_at_client` - Timestamp when updated on client
- `synced_at` - Timestamp when synced to server

### **Sync Logic**
```php
// Server-side duplicate check
$existing = LogPenyimpananLimbah::where('client_uuid', $clientUuid)->first();
if ($existing) {
    // Update existing record
    $existing->update($data);
} else {
    // Create new record
    LogPenyimpananLimbah::create($data);
}
```

---

## 🐛 COMMON ISSUES & SOLUTIONS

### **Issue 1: Array vs Object Access**
**Problem:** Blade template tries to access array as object
```blade
{{ $log->namaLimbah }} // Error: Attempt to read property on array
```

**Solution:** Check if data is array or object
```blade
{{ $log['jenis_limbah']['nama_limbah'] }} // For toArray() data
{{ $log->jenisLimbah->nama_limbah }} // For Eloquent objects
```

### **Issue 2: Missing Route Parameters**
**Problem:** Route requires parameter but not provided
```blade
{{ route('log-penyimpanan.show', $log) }}
// Error: Missing required parameter for [Route: log-penyimpanan.show]
```

**Solution:** Include parameter key
```blade
{{ route('log-penyimpanan.show', ['log_penyimpanan' => $log['log_id']]) }}
```

### **Issue 3: Index Not Being Used**
**Problem:** Query is slow despite index exists
```php
// Query uses LOWER() preventing index usage
->whereRaw("LOWER(status_log) != 'diangkut'")
```

**Solution:** Use generated column or case-insensitive collation
```php
->where('status_log', '!=', 'Diangkut') // Can use status_log_lower index
```

### **Issue 4: N+1 Query Problem**
**Problem:** Multiple queries in loop
```php
foreach ($logs as $log) {
    $name = $log->jenisLimbah->nama_limbah; // N+1 queries!
}
```

**Solution:** Eager load relationships
```php
$logs = LogPenyimpananLimbah::with(['jenisLimbah'])->get();
```

---

## 📊 CURRENT STATUS

### **Database**
- **Total Logs:** 60 (sample data)
- **Indexes:** 35+ (including 8 new composite indexes)
- **Status:** Optimized with generated column

### **Performance**
- **Dashboard Load:** < 1 second (optimized from 2-3 seconds)
- **Bulk Operations:** < 300ms for 100 records (95-97% faster)
- **Search/Filter:** < 400ms (60-80% faster)

### **Features Implemented**
- ✅ Dashboard with statistics and charts
- ✅ Log CRUD with approval workflow
- ✅ Offline mode with API sync
- ✅ Audit logging for all operations
- ✅ Reporting with export functionality
- ✅ Waste expiry tracking and warnings
- ✅ Bulk operations for approvals
- ✅ Multi-unit access control

---

## 🎯 DEVELOPMENT WORKFLOW

### **When Making Changes**

1. **Understand the Pattern**
   - Check existing similar code
   - Follow service layer pattern
   - Use existing helpers/traits

2. **Write Database-Changes**
   - Create migration
   - Test migration locally
   - Consider rollback plan

3. **Write Business Logic**
   - Add method to appropriate Service
   - Keep controllers thin
   - Handle exceptions properly

4. **Update Views**
   - Check if data is array or object
   - Use correct access pattern
   - Include all necessary relationships

5. **Test Thoroughly**
   - Use Tinker for quick tests
   - Test with sample data
   - Check performance

6. **Document Changes**
   - Update this file if needed
   - Add comments to code
   - Consider updating development-notes

### **Before Pushing to Production**

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 2. Run tests
php artisan test

# 3. Check migrations
php artisan migrate:status

# 4. Verify performance
# Check slow query log
# Verify indexes are being used

# 5. Backup database
mysqldump -u user -p waspro > backup_$(date +%Y%m%d).sql

# 6. Deploy
git pull
composer install --optimize-autoloader
php artisan migrate --force
php artisan queue:restart
```

---

## 📞 GETTING STARTED FOR NEW AI AGENTS

### **Step 1: Understand the Context**
- Read `development-notes.md` for recent changes
- Check `agent.md` for architecture and patterns
- Review the current database schema

### **Step 2: Explore the Codebase**
- Start with Service layer for business logic
- Check Controllers for request handling
- Review Models for relationships
- Look at migrations for schema understanding

### **Step 3: Follow the Patterns**
- Use existing services as templates
- Follow naming conventions strictly
- Test with Tinker before finalizing
- Consider performance implications

### **Step 4: Collaborate**
- Read existing code before writing new
- Reuse existing helpers and utilities
- Maintain consistency with existing patterns
- Document any significant changes

---

## 🔧 UTILITIES & HELPERS

### **Common Tinker Commands**
```bash
# Check user authentication
php artisan tinker --execute="Auth::guard('web')->user();"

# Count records
php artisan tinker --execute="App\Models\LogPenyimpananLimbah::count();"

# Test query performance
php artisan tinker --execute="
DB::enableQueryLog();
App\Models\LogPenyimpananLimbah::with(['jenisLimbah'])->get();
Log::debug(DB::getQueryLog());
"

# Create sample data
php artisan tinker --execute="include 'tinker_create_sample_logs.php';"
```

### **Useful Artisan Commands**
```bash
php artisan migrate:status          # Check migration status
php artisan migrate:rollback       # Rollback last migration
php artisan route:list              # List all routes
php artisan config:cache            # Cache configuration
php artisan optimize                # Optimize for production
php artisan queue:restart           # Restart queue workers
```

---

## 📚 RESOURCES

- **Laravel Documentation:** https://laravel.com/docs/12.x
- **MariaDB Performance Tuning:** https://mariadb.com/kb/en/performance-optimization/
- **Laravel Performance Best Practices:** https://github.com/alexeymezenin/laravel-best-practices

---

## 🎓 KEY LEARNINGS FROM PROJECT

1. **Service Layer is Critical**: Keep business logic out of controllers
2. **Indexes Matter**: Proper indexes can improve performance by 80%+
3. **Eager Loading is Essential**: Always avoid N+1 queries
4. **Generated Columns are Powerful**: Use for case-insensitive searches
5. **Bulk Operations**: Use `whereIn()` instead of loops for 95%+ speedup
6. **Array vs Object**: Know your data structure in Blade templates
7. **Cache Wisely**: Cache expensive computations but clear on updates
8. **Test with Tinker**: Quick verification before writing full code

---

**Last Updated:** 2026-01-19
**Version:** 1.0
**Maintained By:** AI Agents working on WASPRO project

---

> **Note:** This document should be updated when significant architectural changes occur or when new patterns are established.
