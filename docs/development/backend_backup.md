# ⚙️ Backend Development Notes

## Overview
This document tracks all backend-related changes and considerations for WASPRO application.

---

## [2026-01-15] [Fix] Log Penyimpanan Create - Super Admin NULL unit_id

**Files Changed:**
- `app/Http/Controllers/LogPenyimpananLimbahController.php` - Updated store() and create() methods to handle Super Admin with NULL unit_id
- `resources/views/log-penyimpanan/create.blade.php` - Added conditional Unit Pembangkit dropdown for Super Admin

**Reasoning:**
- Super Admin users have unit_id = NULL as per PRD (to access all units)
- When Super Admin tried to create log penyimpanan, the store() method used user's unit_id (NULL)
- UnitPembangkit::where('unit_id', NULL)->exists() returned false, causing silent validation failure
- Form redirected back to create page without saving data and without error message (unit_id error not displayed)

**Impact:**
- Super Admin can now create log penyimpanan records
- Unit Pembangkit dropdown appears for Super Admin users without assigned unit
- Regular users (with assigned unit) continue to use their unit automatically
- No changes to non-Super Admin user experience

**Implementation:**
1. Controller create() method:
   - Added `requiresUnitSelection` flag based on user's isSuperAdmin() and empty unit_id
   - Passed flag to view for conditional field display
2. Controller store() method:
   - Added conditional validation rule: unit_id required only for Super Admin with NULL unit_id
   - Logic to use form's unit_id for Super Admin, user's unit_id for others
3. View create.blade.php:
   - Added Unit Pembangkit select dropdown with @if($requiresUnitSelection)
   - Added helper text explaining the field purpose
   - Added error display for unit_id validation

---

## [2026-01-15] [Fix] Jenis Limbah Create Error - Kategori Relationship

**Files Changed:**
- `app/Models/JenisLimbah.php` - Added kategori_id to fillable, added relationship method kategoriKegiatanSumber()
- `app/Models/KategoriKegiatanSumber.php` - Added HasMany relationship to JenisLimbah, fixed foreign key in logPenyimpananLimbah
- `app/Http/Controllers/JenisLimbahController.php` - Added kategoriKegiatanSumber to create/edit methods, updated eager loading
- `resources/views/jenis-limbah/edit.blade.php` - Updated dropdown to use $kategoriKegiatanSumber variable from controller
- `resources/views/jenis-limbah/index.blade.php` - Added Kategori column to table, updated colspan from 7 to 8
- `resources/views/jenis-limbah/show.blade.php` - Added Kategori information display (similar to Karakteristik)

**Reasoning:**
- kategori_id exists in database but not in Model fillable array causing NULL constraint violation
- Missing relationship between JenisLimbah and KategoriKegiatanSumber
- Controller not passing kategori data to view causing undefined variable error
- Views not displaying kategori information in table and detail page


**Impact:**
- User can now create jenis limbah with proper kategori selection
- Dropdown kategori displays all data kategori_kegiatan_sumber
- Relationship $jenisLimbah->kategoriKegiatanSumber() works correctly
- All CRUD operations work without errors
- Index and show pages now display kategori information

**Implementation:**
1. JenisLimbah Model:
   - Added 'kategori_id' to fillable array after 'karakteristik_id'
   - Added kategoriKegiatanSumber() BelongsTo relationship
   - Imported KategoriKegiatanSumber class
2. KategoriKegiatanSumber Model:
   - Fixed logPenyimpananLimbah() HasMany with correct foreign key 'kategori_id'
   - Added jenisLimbah() HasMany relationship
   - Imported JenisLimbah class
3. JenisLimbahController:
   - Imported KategoriKegiatanSumber class
   - Added $kategoriKegiatanSumber query to create() and edit() methods
   - Updated eager loading in index() and show() to include 'kategoriKegiatanSumber'
4. Views:
   - edit.blade.php: Updated dropdown to use $kategoriKegiatanSumber variable from controller
   - index.blade.php: Added Kategori column, updated colspan to 8
   - show.blade.php: Added Kategori information display (similar to Karakteristik)

---

## [2026-01-15] [Feature] Add Uraian Pekerjaan Field to Log Penyimpanan

**Files Changed:**
- `database/migrations/2026_01_14_230745_add_uraian_pekerjaan_to_log_penyimpanan_limbah_table.php` - New migration
- `app/Models/LogPenyimpananLimbah.php` - Added uraian_pekerjaan to fillable
- `app/Http/Controllers/LogPenyimpananLimbahController.php` - Added validation, search filter
- `resources/views/log-penyimpanan/index.blade.php` - Added search input and table column
- `resources/views/log-penyimpanan/create.blade.php` - Added form field
- `resources/views/log-penyimpanan/edit.blade.php` - Added form field with pre-filled value
- `resources/views/log-penyimpanan/show.blade.php` - Added detail display
- `resources/views/log-penyimpanan/export-pdf.blade.php` - Added column to export
- `app/Exports/LogIndexExport.php` - Added uraian_pekerjaan to Excel export

**Reasoning:**
- User needs to record and search waste logs by work description
- Field "detail_sumber_limbah" only stores category name, not work description
- No way to search by "limbah bekas pekerjaan apa"
- Need dedicated field for work description with good UX

**Impact:**
- Users can now record work description for each waste log entry
- Search functionality added to filter by uraian_pekerjaan
- Table displays truncated text with tooltip for UX
- Create/Edit/Show pages updated with proper form fields
- PDF/Excel exports include uraian_pekerjaan data

**Implementation:**
1. Database: Added text column uraian_pekerjaan (nullable, max 1000 chars)
2. Model: Added to fillable array
3. Controller: Added validation, search filter for uraian_pekerjaan
4. Views: Added search input, table column with truncation, form fields with textarea, detail display
5. Export: Added column to PDF/Excel exports

**UX Considerations:**
- Textarea with 3 rows for better user experience
- Truncate at 50 chars in table with tooltip for full text
- Search input prominently placed in filter section
- Placeholder text guides user input
- Help text explains character limit (1000 chars)

---

## [2026-01-15] [Fix] Kategori Kegiatan Sumber Index Error

**Files Changed:**
- `app/Models/KategoriKegiatanSumber.php` - Removed invalid logPenyimpananLimbah relationship (line 22-28)
- `app/Http/Controllers/KategoriKegiatanSumberController.php` - Removed withCount('logPenyimpananLimbah'), removed load('logPenyimpananLimbah'), removed validation in destroy (lines 15, 49, 81-90)

**Reasoning:**
- log_penyimpanan_limbah table does NOT have kategori_id column
- Table has detail_sumber_limbah (TEXT) which stores category name, not ID
- Previous fix incorrectly set relationship to use kategori_id foreign key
- KategoriKegiatanSumber::logPenyimpananLimbah() relationship is invalid and causes error
- Controller's withCount() and load() methods use this invalid relationship

**Impact:**
- Kategori Kegiatan Sumber index page now accessible without error
- Invalid logPenyimpananLimbah relationship removed
- Controller simplified to remove broken relationship calls
- Error "no such column: log_penyimpanan_limbah.kategori_id" resolved

**Implementation:**
1. KategoriKegiatanSumber Model:
   - Removed logPenyimpananLimbah() HasMany relationship (invalid)
   - Keep jenisLimbah() HasMany relationship (valid)
2. KategoriKegiatanSumberController:
   - Removed withCount('logPenyimpananLimbah') from index()
   - Removed load('logPenyimpananLimbah') from show()
   - Removed validation check in destroy() for logPenyimpananLimbah usage
   - Controller now works correctly with only valid relationships

---

## [2026-01-11] Fix: Internal Server Error on Login/Dashboard

**Files Changed:**

**1. Routes (`routes/web.php`):**
# ⚙️ Backend Development Notes

## Overview
This document tracks all backend-related changes and considerations for the WASPRO application.

---

## [2026-01-11] [Fix] Internal Server Error on Login/Dashboard

**Files Changed:**

**1. Routes (`routes/web.php`):**
- Added missing dashboard route with `auth` and `unit.access` middleware (lines 28-33)
- Fixed logout route naming - POST login was incorrectly named `logout` (line 16-17)
- Added ALL missing resource routes that layout sidebar references (lines 48-95):
  - `log-penyimpanan` (resource + approve/reject)
  - `pengangkutan-limbah` (resource)
  - `reports` (index, monthly, status, waste-type, company, unit, export)
  - `perusahaan-penghasil` (resource)
  - `unit-pembangkit` (resource)
  - `pengguna-sistem` (resource)
  - `peran-pengguna` (resource)
  - `jenis-limbah` (resource)
  - `karakteristik-limbah` (resource)
  - `kategori-kegiatan-sumber` (resource)
  - `application-settings` (resource)
  - `notifications` (get-count, get-expiry-notifications)

**2. Model (`app/Models/PenggunaSistem.php`):**
- Removed duplicate method declarations:
  - `markEmailAsVerified()` declared 3 times → now 1 time
  - `sendEmailVerificationNotification()` declared 2 times → now 1 time
  - `getEmailForPasswordReset()` declared 2 times → now 1 time
- Added missing interface import: `use Illuminate\Contracts\Auth\CanResetPassword;` (line 6)

**3. Layout (`resources/views/layouts/app.blade.php`):**
- Fixed notifications menu: converted HTML comments (`<!-- -->`) to Blade comments (`{{-- --}}`) (lines 286-296)
  - HTML comments still evaluate Blade directives, causing `RouteNotFoundException`
- Fixed notifications dropdown link: replaced `route('notifications.index')` with `#` (line 535)

**Reasoning:**
- Dashboard route `dashboard` was referenced in redirects but never defined
- Layout sidebar referenced many resource routes that didn't exist in `web.php` (only in `api.php`)
- Duplicate methods caused PHP FatalError: `Cannot redeclare`
- Missing CanResetPassword interface import caused `Interface not found` error
- Blade evaluates `{{ route() }}` inside HTML comments, so must use Blade comments

**Impact:**
- Backend: Login redirects to dashboard correctly, all menu items accessible
- API: No changes
- Frontend: Dashboard and all sidebar menu items now work
- Testing: Login flow verified with `superadmin@waspro.com` / `password123`

---


## [2026-01-11] Phase 1: Super Admin & UnitScope (COMPLETED)

**Files Changed:**
- `database/migrations/2026_01_11_073411_fix_status_aktif_to_status_aktif.php` - Fix typo status_aktif to status_aktif
- `app/Models/Scopes/UnitScope.php` - Handle NULL unit_id, remove debug logs
- `database/seeders/PenggunaSistemSeeder.php` - Super Admin now uses unit_id instead of NULL (temporary solution)

**Database Migrations:**
- Migration to rename `status_aktif` to `status_aktif` in 4 tables (jenis_limbah, karakteristik_limbah, perusahaan_penghasil, unit_pembangkit)

**Description:**
- Fixed typo `status_aktif` → `status_aktif` across all tables using migration
- Updated UnitScope to handle NULL unit_id (Super Admin bypass filter)
- Removed debug logging from UnitScope for cleaner code
- Super Admin now has unit_id pointing to "Unit Pembangkit Pusat" (temporary workaround for database constraint)

**Note:**
- Database constraint `unit_id NOT NULL` in pengguna_sistem table conflicts with PRD requirement
- Temporary solution: Super Admin assigned to "Unit Pembangkit Pusat"
- Future improvement: Make unit_id nullable in pengguna_sistem table

---

## [2026-01-11] Phase 2: Approval Workflow (COMPLETED)

**Files Changed:**
- `database/migrations/2026_01_11_073517_add_approval_columns_to_log_penyimpanan_limbah_table.php` - Add approval_status, approved_by, approved_at, rejected_reason
- `database/migrations/2026_01_11_073517_create_approval_log_table.php` - Create approval_log table
- `app/Models/ApprovalLog.php` - Approval log model with relationships
- `app/Models/LogPenyimpananLimbah.php` - Add approval columns to fillable, casts, relationships
- `app/Models/PenggunaSistem.php` - Add isSupervisor(), canApproveLogs() methods
- `app/Http/Controllers/LogPenyimpananLimbahController.php` - Add approve(), reject() methods, update store() with pending status
- `routes/web.php` - Add approve/reject routes
- `routes/api.php` - Add approve/reject API routes
- `app/Http/Controllers/Api/LogPenyimpananController.php` - Add approve(), reject() API methods
- `app/Http/Controllers/PengangkutanLimbahController.php` - Fixed typo status_aktif → status_aktif

**Database Schema Changes:**
- Add columns to `log_penyimpanan_limbah`:
  - `approval_status` (enum: pending/approved/rejected)
  - `approved_by` (FK to pengguna_sistem.user_id)
  - `approved_at` (timestamp, nullable)
  - `rejected_reason` (text, nullable)
- Create table `approval_log`:
  - `id` (PK)
  - `log_id` (FK to log_penyimpanan_limbah.log_id)
  - `approved_by` (FK to pengguna_sistem.user_id)
  - `action` (enum: approve/reject)
  - `rejected_reason` (text, nullable)
  - `timestamps`

**Workflow Implementation:**
- Operator input → Status = "Pending Approval" (approval_status: pending)
- Supervisor approve → Status = "Approved" (approval_status: approved, approved_at set)
- Supervisor reject → Status = "Rejected" (approval_status: rejected, rejected_reason set)
- All approvals logged in `approval_log` table

**Routes Added:**
- Web: `POST /log-penyimpanan/{id}/approve`
- Web: `POST /log-penyimpanan/{id}/reject`
- API: `POST /api/log-penyimpanan/{id}/approve`
- API: `POST /api/log-penyimpanan/{id}/reject`

**Reasoning:**
- Implements approval workflow as per PRD requirement
- Logs all approval actions for audit trail
- Prevents operator from editing approved/rejected logs
- Only Supervisor, Admin, Super Admin can approve/reject

**Impact:**
- Database: New table and columns for approval tracking
- Backend: New model and controller methods
- Frontend: Views need update to show approval status and approve/reject buttons
- API: New endpoints for Flutter app integration
- Testing: Need tests for approval workflow

---

## [2026-01-11] Phase 3: Audit Trail (PENDING)

**Status:**
- Migration file created: `2026_01_11_073517_create_audit_log_table.php` (template, not filled)
- Model files need creation
- AuditTrait needs creation
- Implementation not started yet

---

## [2026-01-11] Phase 4: Sistem Biaya (PENDING)

**Status:**
- Not started yet
- Will add biaya columns to jenis_limbah table
- Will add CRUD for biaya management

---

## [2026-01-11] Phase 5: Code Cleanup (PARTIALLY COMPLETED)

**Files Changed:**
- `app/Http/Controllers/PengangkutanLimbahController.php` - Fixed typo `status_aktif` → `status_aktif`

**Status:**
- Some code cleanup completed (typo fixes)
- Full cleanup deferred to end of all phases

---

## Backend To-Do (Updated)

### Phase 1: Foundation ✅
- [x] Update `UnitScope` to handle NULL unit_id
- [x] Remove debug logging from `UnitScope`
- [x] Implementasi approval workflow
- [x] Add approval columns to `log_penyimpanan_limbah`
- [x] Create `approval_log` table
- [x] Create `ApprovalLog` model
- [x] Update `LogPenyimpananLimbah` model with approval relationships
- [x] Update `LogPenyimpananLimbahController` with approve/reject methods
- [x] Update web routes with approve/reject endpoints
- [x] Update API routes with approve/reject endpoints
- [x] Update `LogPenyimpananController` API with approve/reject methods
- [x] Fix typo `status_aktif` → `status_aktif` (migration)
- [x] Update `PenggunaSistem` model with isSupervisor(), canApproveLogs()
- [ ] Make `unit_id` nullable in `pengguna_sistem` table (deferred due to DB constraint)

### Phase 2: Audit Trail
- [ ] Create audit_log table migration
- [ ] Create AuditLog model
- [ ] Create AuditTrait for auto-logging
- [ ] Apply trait to models
- [ ] Create AuditLogController
- [ ] Add audit log routes
- [ ] Write tests for audit trail

### Phase 3: Sistem Biaya
- [ ] Add biaya columns to jenis_limbah table
- [ ] Update JenisLimbah model
- [ ] Update JenisLimbahController
- [ ] Update views for biaya input
- [ ] Update JenisLimbahSeeder with sample biaya
- [ ] Write tests for biaya system

### Scope Implementation
- [x] Update `UnitScope` to handle NULL unit_id
- [ ] Add UnitScope to `PenggunaSistem` (currently commented out)
- [ ] Add UnitScope to `JenisLimbah` (if needed)
- [ ] Add UnitScope to `PerusahaanPenghasil` (if needed)
- [ ] Add UnitScope to `UnitPembangkit` (if needed)

### API Improvements
- [x] Add approval endpoints (approve/reject)
- [ ] Add audit trail endpoints
- [ ] Add biaya endpoints (for Phase 4)
- [ ] Document all endpoints in OpenAPI spec
- [ ] Implement pagination for all list endpoints
- [ ] Add filtering and sorting capabilities
- [ ] Add rate limiting by role

### Performance
- [ ] Add database indexes for frequently queried columns
- [ ] Implement caching for reports
- [ ] Optimize N+1 queries
- [ ] Add query logging for slow queries

### Security
- [x] Implement audit trail foundation (approval_log table)
- [ ] Implement request throttling
- [ ] Add IP-based rate limiting
- [ ] Implement API key rotation
- [ ] Add notification system for Super Admin problems/expiry

### Testing
- [ ] Write unit tests for approval workflow
- [ ] Write unit tests for UnitScope
- [ ] Write feature tests for approval workflow
- [ ] Write unit tests for AuditLog
- [ ] Setup GitHub Actions for auto-test
- [ ] Write tests for biaya system (Phase 4)

---

**Last Updated:** 2026-01-11

### Framework & Language
- **Framework:** Laravel 12.x
- **PHP Version:** 8.2+
- **Architecture:** MVC (Model-View-Controller)

### Database
- **Development:** SQLite (`database/database.sqlite`)
- **Production:** MySQL 8+ (configured in `.env`)
- **Migrations:** All in `database/migrations/`
- **Seeders:** All in `database/seeders/`

### Key Models

| Model | Table | Primary Key | Description |
|-------|-------|-------------|-------------|
| `PenggunaSistem` | `pengguna_sistem` | `user_id` | System users |
| `UnitPembangkit` | `unit_pembangkit` | `unit_id` | Power generation units |
| `LogPenyimpananLimbah` | `log_penyimpanan_limbah` | `log_id` | Waste storage logs |
| `JenisLimbah` | `jenis_limbah` | `kode_limbah` | Waste types |
| `PerusahaanPenghasil` | `perusahaan_penghasil` | `perusahaan_id` | Companies producing waste |
| `PeranPengguna` | `peran_pengguna` | `peran_id` | User roles |
| `KarakteristikLimbah` | `karakteristik_limbah` | `karakteristik_id` | Waste characteristics |
| `KategoriKegiatanSumber` | `kategori_kegiatan_sumber` | `kategori_id` | Activity categories |

### Scopes Implementation

| Scope | Models Applied | Status | Notes |
|-------|----------------|--------|-------|
| `UnitScope` | `LogPenyimpananLimbah` | ✅ Active | Filters by user's unit_id |
| `UnitScope` | `PenggunaSistem` | ⏸️ Inactive | Commented out in boot() |

### Controllers

| Controller | Routes | Description |
|------------|--------|-------------|
| `DashboardController` | `/dashboard` | Main dashboard |
| `LogPenyimpananLimbahController` | `/log-penyimpanan` | Waste log CRUD |
| `PenggunaSistemController` | `/pengguna-sistem` | User management |
| `UnitPembangkitController` | `/unit-pembangkit` | Unit management |
| `PerusahaanPenghasilController` | `/perusahaan-penghasil` | Company management |
| `JenisLimbahController` | `/jenis-limbah` | Waste type management |
| `ReportController` | `/reports` | Reports (PDF/Excel) |
| `ProfileController` | `/profile` | User profile |
| `PengangkutanLimbahController` | `/pengangkutan-limbah` | Waste transportation management (partial) |

---

## Backend Guidelines

### 1. Model Conventions
- Use Indonesian for table names (e.g., `log_penyimpanan_limbah`)
- Use English for Model class names (e.g., `LogPenyimpananLimbah`)
- Define relationships explicitly
- Use scopes for common queries

### 2. Controller Conventions
- Use Route Model Binding where possible
- Implement validation via Form Request classes
- Return consistent response formats
- Handle exceptions gracefully

### 3. Database Conventions
- Use migrations for all schema changes
- Seed reference data (roles, waste types, etc.)
- Use foreign keys with cascade/delete rules
- Add indexes for frequently queried columns

### 4. Service Layer
- Create services for complex business logic
- Keep controllers thin
- Services should be testable
- Use dependency injection

### 5. API Development
- Follow RESTful conventions
- Use OpenAPI specification in `docs/openapi/k3-api.yaml`
- Document all endpoints for mobile developers
- Use proper HTTP status codes

### 6. Background Jobs
- Use queues for time-consuming tasks
- Database queue driver for development
- Redis/Supervisor for production
- Handle failed jobs properly

---

## Change Log Template

```markdown
### [YYYY-MM-DD] [Type] Description

**Files Changed:**
- `app/Models/Example.php` - Description (line X)
- `app/Http/Controllers/ExampleController.php` - Description (line Y)
- `database/migrations/YYYY_MM_DD_HHMMSS_migration_name.php` - Description

**Reasoning:**
Why the change was made

**Impact:**
- Database: Schema changes, migrations
- API: New/modified endpoints
- Frontend: Required UI changes
- Testing: Tests that need updating
```

---

## Backend To-Do

### Scope Implementation
- [ ] Update `UnitScope` to handle NULL `unit_id` for Super Admin
- [ ] Add UnitScope to `PenggunaSistem` (currently commented out)
- [ ] Add UnitScope to `JenisLimbah` (if needed)
- [ ] Add UnitScope to `PerusahaanPenghasil` (if needed)
- [ ] Add UnitScope to `UnitPembangkit` (if needed)

### API Improvements
- [ ] Document all endpoints in OpenAPI spec
- [ ] Implement pagination for all list endpoints
- [ ] Add filtering and sorting capabilities
- [ ] Add rate limiting by role
- [ ] Add approval endpoints (approve/reject) for supervisor
- [ ] Add audit trail endpoints
- [ ] Add biaya (cost) endpoints
- [ ] Add KPI/dashboard endpoints for compliance monitoring

### Performance
- [ ] Add database indexes for frequently queried columns
- [ ] Implement caching for reports
- [ ] Optimize N+1 queries
- [ ] Add query logging for slow queries

### Security
- [ ] Implement request throttling
- [ ] Add IP-based rate limiting
- [ ] Implement API key rotation
- [ ] Add audit logging table and system
- [ ] Implement notification system for Super Admin on problems/expiry

---

## Database Schema Notes

### Important Tables

#### `pengguna_sistem`
- Links to `unit_pembangkit` via `unit_id`
- Links to `peran_pengguna` via `pengguna_peran` (pivot)

#### `log_penyimpanan_limbah`
- Links to `pengguna_sistem` via `user_id`
- Links to `unit_pembangkit` via `unit_id`
- Links to `jenis_limbah` via `kode_limbah`
- Links to `perusahaan_penghasil` via `perusahaan_id`
- Has `UnitScope` applied automatically

#### `peran_pengguna`
- Roles: Super Admin, Administrator, Supervisor, Operator, Viewer
- Pivot table: `pengguna_peran`
- **Super Admin** should have `unit_id = NULL` for global access
- **Supervisor** has POV like Management (View, Approve, Reject)
- **Operator** only input logs
- **Viewer** read-only access

---

## Backend Commands Reference

### Migrations
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Fresh with seeds
php artisan migrate:fresh --seed
```

### Models
```bash
# Create model with migration and factory
php artisan make:model ModelName -m -f

# Create model with migration, factory, and seeder
php artisan make:model ModelName -m -f -s
```

### Controllers
```bash
# Create controller with resource methods
php artisan make:controller NameController --resource

# Create controller with model binding
php artisan make:controller NameController --model=ModelName

# Create API controller
php artisan make:controller NameController --api
```

### Requests
```bash
# Create form request
php artisan make:request StoreExampleRequest
```

---

## Backend Checklist

Before completing backend changes:
- [ ] Code follows existing patterns
- [ ] Database migration created (if schema change)
- [ ] Relationships defined correctly
- [ ] Validation implemented
- [ ] Error handling added
- [ ] Tests written/updated
- [ ] Documentation updated
- [ ] API documentation updated (if applicable)

---

## PRD Implementation Notes

### Key PRD Requirements
- **Super Admin**: Should have `unit_id = NULL` for global access
- **Supervisor**: Should have POV like Management (View, Approve, Reject)
- **Approval Workflow**: Operator input → Pending → Supervisor Approve/Reject
- **Biaya (Cost)**: Per waste type (jenis limbah), focus on transportation cost
- **Scale**: 40-50 unit organizations, 120-250 total users
- **Compliance**: PROPER HIJAU format (to be defined when regulations are available)
- **No Auditor Role**: Supervisor performs approval function
- **Notifications**: Super Admin receives notifications for problems/expiry

---

**Last Updated:** 2026-01-11

## [2026-01-12] Phase Complete: Fix Critical Errors

**Files Changed:**
- `routes/web.php` - Added 3 missing routes (lines 54-73):
  - `GET /log-penyimpanan/export` → LogPenyimpananLimbahController@export
  - `GET /pengangkutan-limbah/diangkut` → PengangkutanLimbahController@diangkut
  - `GET /expiry-reports/` & `/export` → ExpiryReportController@index & export
- `database/migrations/2026_01_11_223547_fix_expiry_status_enum_safe.php` - Fix expiry status enum (created)
- `app/Models/LogPenyimpananLimbah.php` - Fixed expiry calculation to match UI (line 179-202)
- `database/seeders/JenisLimbahSeeder.php` - Added biaya data to all 5 waste types (lines 12-62)

**Reasoning:**
- Routes were missing causing menu errors and export functionality to fail
- Expiry status enum had "Normal" instead of "Safe", conflicting with UI
- Expiry calculation used 3 days for Critical, 7 days for Warning, but UI shows Critical = 1-7 days, Warning = 8-30 days
- JenisLimbahSeeder referenced non-existent biaya fields, would fail on migrate:fresh --seed

**Impact:**
- Application menus now work without RouteNotFoundException
- Expiry reports page accessible
- Log export functionality works
- Pengangkutan diangkut page accessible
- Database now uses correct expiry status enum values
- Expiry calculation logic matches UI expectations
- JenisLimbahSeeder can run successfully with biaya data

---

## [2026-01-12] Phase Complete: Editable Biaya System

**Files Changed:**
- `app/Http/Controllers/JenisLimbahController.php` - Added biaya validation to store() and update() (lines 38-45, 81-88)
- `resources/views/jenis-limbah/create.blade.php` - Added biaya form section with 4 input fields (lines 119-168)
- `resources/views/jenis-limbah/edit.blade.php` - Added biaya form section with pre-filled values (lines 37-117)
- `resources/views/jenis-limbah/show.blade.php` - Added biaya information card (lines 109-155)
- `resources/views/jenis-limbah/index.blade.php` - Added biaya column to table header and rows (lines 45, 81-86)

**Reasoning:**
- Users requested biaya to be editable via UI, not hardcoded
- Added validation rules: biaya_pengangkutan_per_kg (min:0, no max), mulai_berlaku, akhir_berlaku, keterangan_biaya
- Created comprehensive biaya input form with 4 fields (biaya per kg, mulai_berlaku, akhir_berlaku, keterangan)
- Edit form pre-fills existing biaya values
- Show page displays current biaya, masa berlaku, and keterangan
- Index table shows biaya in proper Rupiah format per kg

**Impact:**
- Users can now manage biaya pengangkutan per jenis limbah through UI
- Biaya history tracked via AuditTrait (auto-logs create/update operations)
- Validation prevents negative or invalid biaya values
- Date validation ensures akhir_berlaku is always after mulai_berlaku
- Display shows biaya in proper Indonesian Rupiah format
- System ready for cost tracking and reporting

---

## [2026-01-12] Phase Complete: Audit Trail Implementation

**Files Changed:**
- Applied AuditTrait to all 13 models in `app/Models/`:
  - AppSetting.php (line 8)
  - ApplicationSetting.php (line 7)
  - JenisLimbah.php (line 10)
  - KarakteristikLimbah.php (line 10)
  - KategoriKegiatanSumber.php (line 10)
  - LogPenyimpananLimbah.php (line 14)
  - PenggunaPeran.php (line 10)
  - PenggunaSistem.php (line 10)
  - PeranPengguna.php (line 10)
  - PerusahaanPenghasil.php (line 10)
  - UnitPembangkit.php (line 10)
- `app/Traits/AuditTrait.php` - Fixed user ID capture with proper auth check (lines 5-50)

**Reasoning:**
- User requested AuditTrait to track all operations on all models in system
- Fixed trait to capture authenticated user ID using auth()->guard("web")->id()
- Returns null for unauthenticated operations
- Logs IP address, user agent, and all model attributes for each operation
- Old values captured on update operations
- AuditLog model already existed with relationships to user

**Impact:**
- Complete audit trail now functional for all data operations
- All create/update/delete operations on all models auto-logged
- Audit log accessible via /audit-log route with export functionality
- System ready for compliance and security auditing
- Returns null user_id for operations without authenticated user

---

## [2026-01-12] Phase Complete: Scheduled Task Verification

**Files Changed:**
- No files modified
- Command executed: `php artisan waste:update-expiry-status --force`

**Reasoning:**
- Verified scheduled task runs correctly
- Command executed successfully with no records to update (expected for fresh database)
- Checked database has correct expiry status enum values
- Confirmed scheduled task will update expiry statuses daily at 1:00 AM

**Impact:**
- Expiry status update system verified working
- Database enum values correct (Safe, Warning, Critical, Expired)
- No "Normal" values remain in database
- Scheduled task configured to run daily as expected
- System ready for automated expiry status updates

---

## Backend To-Do (Updated)

### Phase 1: Foundation ✅
- [x] Update `UnitScope` to handle NULL unit_id
- [x] Remove debug logging from `UnitScope`
- [x] Implementasi approval workflow
- [x] Add approval columns to `log_penyimpanan_limbah`
- [x] Create `approval_log` table
- [x] Create `ApprovalLog` model
- [x] Update `LogPenyimpananLimbah` model with approval relationships
- [x] Update `LogPenyimpananLimbahController` with approve/reject methods
- [x] Update web routes with approve/reject endpoints
- [x] Update API routes with approve/reject endpoints
- [x] Update `LogPenyimpananController` API with approve/reject methods
- [x] Fix typo `status_aktif` → `status_aktif` (migration)
- [x] Update `PenggunaSistem` model with isSupervisor(), canApproveLogs()
- [x] Make `unit_id` nullable in `pengguna_sistem` table (deferred due to DB constraint)

### Phase 2: Audit Trail ✅
- [x] Create audit_log table migration
- [x] Create AuditLog model
- [x] Create AuditTrait for auto-logging
- [x] Apply trait to all models (13 models total)
- [x] Create AuditLogController
- [x] Add audit log routes
- [ ] Write tests for audit trail

### Phase 3: Sistem Biaya ✅
- [x] Add biaya columns to jenis_limbah table
- [x] Update JenisLimbah model
- [x] Update JenisLimbahController
- [x] Update views for biaya input
- [x] Update JenisLimbahSeeder with sample biaya
- [ ] Write tests for biaya system

### Phase 4: Expiry System Fix ✅
- [x] Fix expiry status enum (Normal → Safe)
- [x] Update expiry calculation logic to match UI
- [x] Verify scheduled task runs correctly
- [ ] Write tests for expiry calculation

### Phase 5: Code Cleanup ✅
- [x] Fix route errors (3 missing routes)
- [x] Fix JenisLimbahSeeder biaya data
- [x] Fix biaya validation and forms


---

## [2026-01-12] Phase Complete: Fix Menu Route Errors

**Files Changed:**
- `routes/web.php` - Added missing route (line 59):
  - `POST /pengangkutan-limbah/bulk-approve` → PengangkutanLimbahController@bulkApprove

**Reasoning:**
- pengangkutan-limbah/index.blade.php referenced non-existent route `pengangkutan-limbah.bulk-approve`
- Route was causing RouteNotFoundException error when accessing the page
- Controller method `bulkApprove()` already exists at line 190 of PengangkutanLimbahController
- View expects POST route with selected_logs array parameter
- Route added to match controller method signature and requirements

**Impact:**
- Pengangkutan Limbah bulk approval page now accessible
- No RouteNotFoundException errors when accessing /pengangkutan-limbah
- Supervisor/Admin can use bulk approve functionality without errors

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Phase Complete: Fix Reports Menu Error

**Files Changed:**
- `app/Http/Controllers/ReportController.php` - Added clearReportCache() method (line 578)
- `routes/web.php` - Added reports.clear-cache route (line 71)

**Reasoning:**
- reports/index.blade.php references non-existent route `reports.clear-cache`
- Controller already had clearReportCache() method at line 578 but file was corrupted showing 574 lines instead of 575
- Added route to match what view expects
- Route flushes all cache and returns success message

**Impact:**
- Reports menu page now accessible without RouteNotFoundException
- All report-related caches can be cleared via Refresh Data button
- No more errors when accessing /reports page

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Fix: Administrator Access Control Issue

**Files Changed:**
- `app/Models/PenggunaSistem.php` - Fixed canAccessUnit() method logic (line 273)

**Reasoning:**
- Regular Administrator was able to access all units because `canAccessUnit()` used `isAdmin()` which returns true for both Super Admin and Administrator
- Only Super Admin should have global access to all units
- Regular Administrator should only access their own unit

**Impact:**
- Fixed: Administrator now can only access their assigned unit
- Fixed: Super Admin still has global access to all units
- Security: Proper separation of access control between Super Admin and Administrator

**Implementation:**
Changed line 273 from:
```php
if ($this->isAdmin()) {
```
To:
```php
if ($this->isSuperAdmin()) {
```

This ensures only Super Admin (with `unit_id = NULL`) can access all units, while Administrator can only access their own unit.

---

## [2026-01-12] Feature: Super Admin with NULL unit_id

**Files Changed:**
- `database/migrations/2026_01_12_042200_make_unit_id_nullable_in_pengguna_sistem_table.php` - New migration
- `database/seeders/PenggunaSistemSeeder.php` - Updated Super Admin to have unit_id = NULL (line 40)
- `app/Http/Controllers/PenggunaSistemController.php` - Updated validation and logic (lines 59-91, 154-191)
- `.env.example` - Added SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD, SUPERADMIN_NAME (lines 69-73)
- `tests/Unit/SuperAdminUnitIdTest.php` - New test suite with 6 tests

**Reasoning:**
- PRD requires Super Admin to have global access without unit assignment
- unit_id = NULL allows Super Admin to bypass UnitScope filtering
- Non-Super Admin must have unit_id for proper organization management
- Migration makes unit_id nullable while maintaining foreign key constraint
- Enforces single Super Admin policy for security

**Impact:**
- Database: unit_id now nullable, existing Super Admin migrated to NULL
- Backend: Updated validation to enforce one-Super-Admin policy
- Frontend: Create/Edit user forms must handle NULL unit_id for Super Admin
- Testing: New tests for Super Admin NULL unit_id behavior
- Security: Enforces single Super Admin rule and proper access control

**Implementation:**
1. Migration makes unit_id nullable with proper foreign key handling
2. Seeder now supports .env config for Super Admin credentials
3. Controller validates:
   - Only one Super Admin allowed
   - Super Admin must have unit_id = NULL
   - Non-Super Admin must have unit_id (required)
   - Non-admin cannot create Super Admin
4. Tests verify all scenarios work correctly
5. Migration down method safely reverts to NOT NULL if needed

---

## [2026-01-12] Fix: Jenis Limbah Form Incomplete Fields

**Files Changed:**
- `resources/views/jenis-limbah/create.blade.php` - Added missing fields (lines 31-93):
  - Kode Limbah (kode_limbah) - Primary key
  - Nama Limbah (nama_limbah) - Required
  - Kemasan (kemasan) - Required
  - Deskripsi Limbah (deskripsi_limbah) - Optional
  - Karakteristik Limbah (karakteristik_id) - Optional dropdown
- `resources/views/jenis-limbah/edit.blade.php` - Added missing kemasan field (lines 62-88):
  - Kemasan (kemasan) - Required input
- `app/Http/Controllers/JenisLimbahController.php` - Added kemasan to validation:
  - Line 42 (store): 'kemasan' => 'required|string|max:255'
  - Line 89 (update): 'kemasan' => 'required|string|max:255'

**Reasoning:**
- User reported create form only showing "Waktu Penyimpanan" and "Status Aktif" fields
- Form was missing critical fields that are in database table and model:
  - Kode Limbah (PRIMARY KEY)
  - Nama Limbah (required field)
  - Kemasan (required field)
  - Deskripsi Limbah (optional but important)
  - Karakteristik Limbah (classification dropdown)
- These fields come BEFORE waktu_penyimpanan_hari and status_aktif in form
- Controller validation was missing 'kemasan' field
- Edit form already had all main fields except kemasan

**Impact:**
- Users can now create Jenis Limbah with complete information
- All required fields are present in create form
- Edit form has complete set of fields including kemasan
- Form matches database schema and model requirements
- Biaya section remains functional below other fields

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Investigate: Sass Deprecation Warning

**Files Changed:**
- `resources/sass/_variables.scss` - Attempted @forward syntax (reverted)
- `vite.config.js` - No changes made

**Reasoning:**
- `npm run build` shows deprecation warning for `@import 'variables'` in Dart Sass 3.0.0
- Attempted to fix by using `@forward` syntax but this caused build to fail completely
- `@forward` only works for Sass modules with `@mixin` and `@forward`, not for simple variable files
- Reverted to original `@import 'variables'` syntax

**Current Status:**
- Build completes successfully with deprecation warning (non-breaking)
- This is informational warning from Dart Sass 3.0.0
- Code continues to work correctly
- Proper fix requires refactoring to CSS custom properties or other major changes

**Recommendation:**
- Accept deprecation warning for now (non-breaking)
- Consider future refactoring to CSS custom properties if warnings become critical
- Build time: 920ms (acceptable)

**Last Updated:** 2026-01-12
**1. Routes (`routes/web.php`):**
---

## [2026-01-11] [Fix] Internal Server Error on Login/Dashboard

**Files Changed:**

**1. Routes (`routes/web.php`):**
- Added missing dashboard route with `auth` and `unit.access` middleware (lines 28-33)
- Fixed logout route naming - POST login was incorrectly named `logout` (line 16-17)
- Added ALL missing resource routes that layout sidebar references (lines 48-95):
  - `log-penyimpanan` (resource + approve/reject)
  - `pengangkutan-limbah` (resource)
  - `reports` (index, monthly, status, waste-type, company, unit, export)
  - `perusahaan-penghasil` (resource)
  - `unit-pembangkit` (resource)
  - `pengguna-sistem` (resource)
  - `peran-pengguna` (resource)
  - `jenis-limbah` (resource)
  - `karakteristik-limbah` (resource)
  - `kategori-kegiatan-sumber` (resource)
  - `application-settings` (resource)
  - `notifications` (get-count, get-expiry-notifications)

**2. Model (`app/Models/PenggunaSistem.php`):**
- Removed duplicate method declarations:
  - `markEmailAsVerified()` declared 3 times → now 1 time
  - `sendEmailVerificationNotification()` declared 2 times → now 1 time
  - `getEmailForPasswordReset()` declared 2 times → now 1 time
- Added missing interface import: `use Illuminate\Contracts\Auth\CanResetPassword;` (line 6)

**3. Layout (`resources/views/layouts/app.blade.php`):**
- Fixed notifications menu: converted HTML comments (`<!-- -->`) to Blade comments (`{{-- --}}`) (lines 286-296)
  - HTML comments still evaluate Blade directives, causing `RouteNotFoundException`
- Fixed notifications dropdown link: replaced `route('notifications.index')` with `#` (line 535)

**Reasoning:**
- Dashboard route `dashboard` was referenced in redirects but never defined
- Layout sidebar referenced many resource routes that didn't exist in `web.php` (only in `api.php`)
- Duplicate methods caused PHP FatalError: `Cannot redeclare`
- Missing CanResetPassword interface import caused `Interface not found` error
- Blade evaluates `{{ route() }}` inside HTML comments, so must use Blade comments

**Impact:**
- Backend: Login redirects to dashboard correctly, all menu items accessible
- API: No changes
- Frontend: Dashboard and all sidebar menu items now work
- Testing: Login flow verified with `superadmin@waspro.com` / `password123`

---


## [2026-01-11] Phase 1: Super Admin & UnitScope (COMPLETED)

**Files Changed:**
- `database/migrations/2026_01_11_073411_fix_status_aktif_to_status_aktif.php` - Fix typo status_aktif to status_aktif
- `app/Models/Scopes/UnitScope.php` - Handle NULL unit_id, remove debug logs
- `database/seeders/PenggunaSistemSeeder.php` - Super Admin now uses unit_id instead of NULL (temporary solution)

**Database Migrations:**
- Migration to rename `status_aktif` to `status_aktif` in 4 tables (jenis_limbah, karakteristik_limbah, perusahaan_penghasil, unit_pembangkit)

**Description:**
- Fixed typo `status_aktif` → `status_aktif` across all tables using migration
- Updated UnitScope to handle NULL unit_id (Super Admin bypass filter)
- Removed debug logging from UnitScope for cleaner code
- Super Admin now has unit_id pointing to "Unit Pembangkit Pusat" (temporary workaround for database constraint)

**Note:**
- Database constraint `unit_id NOT NULL` in pengguna_sistem table conflicts with PRD requirement
- Temporary solution: Super Admin assigned to "Unit Pembangkit Pusat"
- Future improvement: Make unit_id nullable in pengguna_sistem table

---

## [2026-01-11] Phase 2: Approval Workflow (COMPLETED)

**Files Changed:**
- `database/migrations/2026_01_11_073517_add_approval_columns_to_log_penyimpanan_limbah_table.php` - Add approval_status, approved_by, approved_at, rejected_reason
- `database/migrations/2026_01_11_073517_create_approval_log_table.php` - Create approval_log table
- `app/Models/ApprovalLog.php` - Approval log model with relationships
- `app/Models/LogPenyimpananLimbah.php` - Add approval columns to fillable, casts, relationships
- `app/Models/PenggunaSistem.php` - Add isSupervisor(), canApproveLogs() methods
- `app/Http/Controllers/LogPenyimpananLimbahController.php` - Add approve(), reject() methods, update store() with pending status
- `routes/web.php` - Add approve/reject routes
- `routes/api.php` - Add approve/reject API routes
- `app/Http/Controllers/Api/LogPenyimpananController.php` - Add approve(), reject() API methods
- `app/Http/Controllers/PengangkutanLimbahController.php` - Fixed typo status_aktif → status_aktif

**Database Schema Changes:**
- Add columns to `log_penyimpanan_limbah`:
  - `approval_status` (enum: pending/approved/rejected)
  - `approved_by` (FK to pengguna_sistem.user_id)
  - `approved_at` (timestamp, nullable)
  - `rejected_reason` (text, nullable)
- Create table `approval_log`:
  - `id` (PK)
  - `log_id` (FK to log_penyimpanan_limbah.log_id)
  - `approved_by` (FK to pengguna_sistem.user_id)
  - `action` (enum: approve/reject)
  - `rejected_reason` (text, nullable)
  - `timestamps`

**Workflow Implementation:**
- Operator input → Status = "Pending Approval" (approval_status: pending)
- Supervisor approve → Status = "Approved" (approval_status: approved, approved_at set)
- Supervisor reject → Status = "Rejected" (approval_status: rejected, rejected_reason set)
- All approvals logged in `approval_log` table

**Routes Added:**
- Web: `POST /log-penyimpanan/{id}/approve`
- Web: `POST /log-penyimpanan/{id}/reject`
- API: `POST /api/log-penyimpanan/{id}/approve`
- API: `POST /api/log-penyimpanan/{id}/reject`

**Reasoning:**
- Implements approval workflow as per PRD requirement
- Logs all approval actions for audit trail
- Prevents operator from editing approved/rejected logs
- Only Supervisor, Admin, Super Admin can approve/reject

**Impact:**
- Database: New table and columns for approval tracking
- Backend: New model and controller methods
- Frontend: Views need update to show approval status and approve/reject buttons
- API: New endpoints for Flutter app integration
- Testing: Need tests for approval workflow

---

## [2026-01-11] Phase 3: Audit Trail (PENDING)

**Status:**
- Migration file created: `2026_01_11_073517_create_audit_log_table.php` (template, not filled)
- Model files need creation
- AuditTrait needs creation
- Implementation not started yet

---

## [2026-01-11] Phase 4: Sistem Biaya (PENDING)

**Status:**
- Not started yet
- Will add biaya columns to jenis_limbah table
- Will add CRUD for biaya management

---

## [2026-01-11] Phase 5: Code Cleanup (PARTIALLY COMPLETED)

**Files Changed:**
- `app/Http/Controllers/PengangkutanLimbahController.php` - Fixed typo `status_aktif` → `status_aktif`

**Status:**
- Some code cleanup completed (typo fixes)
- Full cleanup deferred to end of all phases

---

## Backend To-Do (Updated)

### Phase 1: Foundation ✅
- [x] Update `UnitScope` to handle NULL unit_id
- [x] Remove debug logging from `UnitScope`
- [x] Implementasi approval workflow
- [x] Add approval columns to `log_penyimpanan_limbah`
- [x] Create `approval_log` table
- [x] Create `ApprovalLog` model
- [x] Update `LogPenyimpananLimbah` model with approval relationships
- [x] Update `LogPenyimpananLimbahController` with approve/reject methods
- [x] Update web routes with approve/reject endpoints
- [x] Update API routes with approve/reject endpoints
- [x] Update `LogPenyimpananController` API with approve/reject methods
- [x] Fix typo `status_aktif` → `status_aktif` (migration)
- [x] Update `PenggunaSistem` model with isSupervisor(), canApproveLogs()
- [ ] Make `unit_id` nullable in `pengguna_sistem` table (deferred due to DB constraint)

### Phase 2: Audit Trail
- [ ] Create audit_log table migration
- [ ] Create AuditLog model
- [ ] Create AuditTrait for auto-logging
- [ ] Apply trait to models
- [ ] Create AuditLogController
- [ ] Add audit log routes
- [ ] Write tests for audit trail

### Phase 3: Sistem Biaya
- [ ] Add biaya columns to jenis_limbah table
- [ ] Update JenisLimbah model
- [ ] Update JenisLimbahController
- [ ] Update views for biaya input
- [ ] Update JenisLimbahSeeder with sample biaya
- [ ] Write tests for biaya system

### Scope Implementation
- [x] Update `UnitScope` to handle NULL unit_id
- [ ] Add UnitScope to `PenggunaSistem` (currently commented out)
- [ ] Add UnitScope to `JenisLimbah` (if needed)
- [ ] Add UnitScope to `PerusahaanPenghasil` (if needed)
- [ ] Add UnitScope to `UnitPembangkit` (if needed)

### API Improvements
- [x] Add approval endpoints (approve/reject)
- [ ] Add audit trail endpoints
- [ ] Add biaya endpoints (for Phase 4)
- [ ] Document all endpoints in OpenAPI spec
- [ ] Implement pagination for all list endpoints
- [ ] Add filtering and sorting capabilities
- [ ] Add rate limiting by role

### Performance
- [ ] Add database indexes for frequently queried columns
- [ ] Implement caching for reports
- [ ] Optimize N+1 queries
- [ ] Add query logging for slow queries

### Security
- [x] Implement audit trail foundation (approval_log table)
- [ ] Implement request throttling
- [ ] Add IP-based rate limiting
- [ ] Implement API key rotation
- [ ] Add notification system for Super Admin problems/expiry

### Testing
- [ ] Write unit tests for approval workflow
- [ ] Write unit tests for UnitScope
- [ ] Write feature tests for approval workflow
- [ ] Write unit tests for AuditLog
- [ ] Setup GitHub Actions for auto-test
- [ ] Write tests for biaya system (Phase 4)

---

**Last Updated:** 2026-01-11

### Framework & Language
- **Framework:** Laravel 12.x
- **PHP Version:** 8.2+
- **Architecture:** MVC (Model-View-Controller)

### Database
- **Development:** SQLite (`database/database.sqlite`)
- **Production:** MySQL 8+ (configured in `.env`)
- **Migrations:** All in `database/migrations/`
- **Seeders:** All in `database/seeders/`

### Key Models

| Model | Table | Primary Key | Description |
|-------|-------|-------------|-------------|
| `PenggunaSistem` | `pengguna_sistem` | `user_id` | System users |
| `UnitPembangkit` | `unit_pembangkit` | `unit_id` | Power generation units |
| `LogPenyimpananLimbah` | `log_penyimpanan_limbah` | `log_id` | Waste storage logs |
| `JenisLimbah` | `jenis_limbah` | `kode_limbah` | Waste types |
| `PerusahaanPenghasil` | `perusahaan_penghasil` | `perusahaan_id` | Companies producing waste |
| `PeranPengguna` | `peran_pengguna` | `peran_id` | User roles |
| `KarakteristikLimbah` | `karakteristik_limbah` | `karakteristik_id` | Waste characteristics |
| `KategoriKegiatanSumber` | `kategori_kegiatan_sumber` | `kategori_id` | Activity categories |

### Scopes Implementation

| Scope | Models Applied | Status | Notes |
|-------|----------------|--------|-------|
| `UnitScope` | `LogPenyimpananLimbah` | ✅ Active | Filters by user's unit_id |
| `UnitScope` | `PenggunaSistem` | ⏸️ Inactive | Commented out in boot() |

### Controllers

| Controller | Routes | Description |
|------------|--------|-------------|
| `DashboardController` | `/dashboard` | Main dashboard |
| `LogPenyimpananLimbahController` | `/log-penyimpanan` | Waste log CRUD |
| `PenggunaSistemController` | `/pengguna-sistem` | User management |
| `UnitPembangkitController` | `/unit-pembangkit` | Unit management |
| `PerusahaanPenghasilController` | `/perusahaan-penghasil` | Company management |
| `JenisLimbahController` | `/jenis-limbah` | Waste type management |
| `ReportController` | `/reports` | Reports (PDF/Excel) |
| `ProfileController` | `/profile` | User profile |
| `PengangkutanLimbahController` | `/pengangkutan-limbah` | Waste transportation management (partial) |

---

## Backend Guidelines

### 1. Model Conventions
- Use Indonesian for table names (e.g., `log_penyimpanan_limbah`)
- Use English for Model class names (e.g., `LogPenyimpananLimbah`)
- Define relationships explicitly
- Use scopes for common queries

### 2. Controller Conventions
- Use Route Model Binding where possible
- Implement validation via Form Request classes
- Return consistent response formats
- Handle exceptions gracefully

### 3. Database Conventions
- Use migrations for all schema changes
- Seed reference data (roles, waste types, etc.)
- Use foreign keys with cascade/delete rules
- Add indexes for frequently queried columns

### 4. Service Layer
- Create services for complex business logic
- Keep controllers thin
- Services should be testable
- Use dependency injection

### 5. API Development
- Follow RESTful conventions
- Use OpenAPI specification in `docs/openapi/k3-api.yaml`
- Document all endpoints for mobile developers
- Use proper HTTP status codes

### 6. Background Jobs
- Use queues for time-consuming tasks
- Database queue driver for development
- Redis/Supervisor for production
- Handle failed jobs properly

---

## Change Log Template

```markdown
### [YYYY-MM-DD] [Type] Description

**Files Changed:**
- `app/Models/Example.php` - Description (line X)
- `app/Http/Controllers/ExampleController.php` - Description (line Y)
- `database/migrations/YYYY_MM_DD_HHMMSS_migration_name.php` - Description

**Reasoning:**
Why the change was made

**Impact:**
- Database: Schema changes, migrations
- API: New/modified endpoints
- Frontend: Required UI changes
- Testing: Tests that need updating
```

---

## Backend To-Do

### Scope Implementation
- [ ] Update `UnitScope` to handle NULL `unit_id` for Super Admin
- [ ] Add UnitScope to `PenggunaSistem` (currently commented out)
- [ ] Add UnitScope to `JenisLimbah` (if needed)
- [ ] Add UnitScope to `PerusahaanPenghasil` (if needed)
- [ ] Add UnitScope to `UnitPembangkit` (if needed)

### API Improvements
- [ ] Document all endpoints in OpenAPI spec
- [ ] Implement pagination for all list endpoints
- [ ] Add filtering and sorting capabilities
- [ ] Add rate limiting by role
- [ ] Add approval endpoints (approve/reject) for supervisor
- [ ] Add audit trail endpoints
- [ ] Add biaya (cost) endpoints
- [ ] Add KPI/dashboard endpoints for compliance monitoring

### Performance
- [ ] Add database indexes for frequently queried columns
- [ ] Implement caching for reports
- [ ] Optimize N+1 queries
- [ ] Add query logging for slow queries

### Security
- [ ] Implement request throttling
- [ ] Add IP-based rate limiting
- [ ] Implement API key rotation
- [ ] Add audit logging table and system
- [ ] Implement notification system for Super Admin on problems/expiry

---

## Database Schema Notes

### Important Tables

#### `pengguna_sistem`
- Links to `unit_pembangkit` via `unit_id`
- Links to `peran_pengguna` via `pengguna_peran` (pivot)

#### `log_penyimpanan_limbah`
- Links to `pengguna_sistem` via `user_id`
- Links to `unit_pembangkit` via `unit_id`
- Links to `jenis_limbah` via `kode_limbah`
- Links to `perusahaan_penghasil` via `perusahaan_id`
- Has `UnitScope` applied automatically

#### `peran_pengguna`
- Roles: Super Admin, Administrator, Supervisor, Operator, Viewer
- Pivot table: `pengguna_peran`
- **Super Admin** should have `unit_id = NULL` for global access
- **Supervisor** has POV like Management (View, Approve, Reject)
- **Operator** only input logs
- **Viewer** read-only access

---

## Backend Commands Reference

### Migrations
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Fresh with seeds
php artisan migrate:fresh --seed
```

### Models
```bash
# Create model with migration and factory
php artisan make:model ModelName -m -f

# Create model with migration, factory, and seeder
php artisan make:model ModelName -m -f -s
```

### Controllers
```bash
# Create controller with resource methods
php artisan make:controller NameController --resource

# Create controller with model binding
php artisan make:controller NameController --model=ModelName

# Create API controller
php artisan make:controller NameController --api
```

### Requests
```bash
# Create form request
php artisan make:request StoreExampleRequest
```

---

## Backend Checklist

Before completing backend changes:
- [ ] Code follows existing patterns
- [ ] Database migration created (if schema change)
- [ ] Relationships defined correctly
- [ ] Validation implemented
- [ ] Error handling added
- [ ] Tests written/updated
- [ ] Documentation updated
- [ ] API documentation updated (if applicable)

---

## PRD Implementation Notes

### Key PRD Requirements
- **Super Admin**: Should have `unit_id = NULL` for global access
- **Supervisor**: Should have POV like Management (View, Approve, Reject)
- **Approval Workflow**: Operator input → Pending → Supervisor Approve/Reject
- **Biaya (Cost)**: Per waste type (jenis limbah), focus on transportation cost
- **Scale**: 40-50 unit organizations, 120-250 total users
- **Compliance**: PROPER HIJAU format (to be defined when regulations are available)
- **No Auditor Role**: Supervisor performs approval function
- **Notifications**: Super Admin receives notifications for problems/expiry

---

**Last Updated:** 2026-01-11

## [2026-01-12] Phase Complete: Fix Critical Errors

**Files Changed:**
- `routes/web.php` - Added 3 missing routes (lines 54-73):
  - `GET /log-penyimpanan/export` → LogPenyimpananLimbahController@export
  - `GET /pengangkutan-limbah/diangkut` → PengangkutanLimbahController@diangkut
  - `GET /expiry-reports/` & `/export` → ExpiryReportController@index & export
- `database/migrations/2026_01_11_223547_fix_expiry_status_enum_safe.php` - Fix expiry status enum (created)
- `app/Models/LogPenyimpananLimbah.php` - Fixed expiry calculation to match UI (line 179-202)
- `database/seeders/JenisLimbahSeeder.php` - Added biaya data to all 5 waste types (lines 12-62)

**Reasoning:**
- Routes were missing causing menu errors and export functionality to fail
- Expiry status enum had "Normal" instead of "Safe", conflicting with UI
- Expiry calculation used 3 days for Critical, 7 days for Warning, but UI shows Critical = 1-7 days, Warning = 8-30 days
- JenisLimbahSeeder referenced non-existent biaya fields, would fail on migrate:fresh --seed

**Impact:**
- Application menus now work without RouteNotFoundException
- Expiry reports page accessible
- Log export functionality works
- Pengangkutan diangkut page accessible
- Database now uses correct expiry status enum values
- Expiry calculation logic matches UI expectations
- JenisLimbahSeeder can run successfully with biaya data

---

## [2026-01-12] Phase Complete: Editable Biaya System

**Files Changed:**
- `app/Http/Controllers/JenisLimbahController.php` - Added biaya validation to store() and update() (lines 38-45, 81-88)
- `resources/views/jenis-limbah/create.blade.php` - Added biaya form section with 4 input fields (lines 119-168)
- `resources/views/jenis-limbah/edit.blade.php` - Added biaya form section with pre-filled values (lines 37-117)
- `resources/views/jenis-limbah/show.blade.php` - Added biaya information card (lines 109-155)
- `resources/views/jenis-limbah/index.blade.php` - Added biaya column to table header and rows (lines 45, 81-86)

**Reasoning:**
- Users requested biaya to be editable via UI, not hardcoded
- Added validation rules: biaya_pengangkutan_per_kg (min:0, no max), mulai_berlaku, akhir_berlaku, keterangan_biaya
- Created comprehensive biaya input form with 4 fields (biaya per kg, mulai_berlaku, akhir_berlaku, keterangan)
- Edit form pre-fills existing biaya values
- Show page displays current biaya, masa berlaku, and keterangan
- Index table shows biaya in proper Rupiah format per kg

**Impact:**
- Users can now manage biaya pengangkutan per jenis limbah through UI
- Biaya history tracked via AuditTrait (auto-logs create/update operations)
- Validation prevents negative or invalid biaya values
- Date validation ensures akhir_berlaku is always after mulai_berlaku
- Display shows biaya in proper Indonesian Rupiah format
- System ready for cost tracking and reporting

---

## [2026-01-12] Phase Complete: Audit Trail Implementation

**Files Changed:**
- Applied AuditTrait to all 13 models in `app/Models/`:
  - AppSetting.php (line 8)
  - ApplicationSetting.php (line 7)
  - JenisLimbah.php (line 10)
  - KarakteristikLimbah.php (line 10)
  - KategoriKegiatanSumber.php (line 10)
  - LogPenyimpananLimbah.php (line 14)
  - PenggunaPeran.php (line 10)
  - PenggunaSistem.php (line 10)
  - PeranPengguna.php (line 10)
  - PerusahaanPenghasil.php (line 10)
  - UnitPembangkit.php (line 10)
- `app/Traits/AuditTrait.php` - Fixed user ID capture with proper auth check (lines 5-50)

**Reasoning:**
- User requested AuditTrait to track all operations on all models in system
- Fixed trait to capture authenticated user ID using auth()->guard("web")->id()
- Returns null for unauthenticated operations
- Logs IP address, user agent, and all model attributes for each operation
- Old values captured on update operations
- AuditLog model already existed with relationships to user

**Impact:**
- Complete audit trail now functional for all data operations
- All create/update/delete operations on all models auto-logged
- Audit log accessible via /audit-log route with export functionality
- System ready for compliance and security auditing
- Returns null user_id for operations without authenticated user

---

## [2026-01-12] Phase Complete: Scheduled Task Verification

**Files Changed:**
- No files modified
- Command executed: `php artisan waste:update-expiry-status --force`

**Reasoning:**
- Verified scheduled task runs correctly
- Command executed successfully with no records to update (expected for fresh database)
- Checked database has correct expiry status enum values
- Confirmed scheduled task will update expiry statuses daily at 1:00 AM

**Impact:**
- Expiry status update system verified working
- Database enum values correct (Safe, Warning, Critical, Expired)
- No "Normal" values remain in database
- Scheduled task configured to run daily as expected
- System ready for automated expiry status updates

---

## Backend To-Do (Updated)

### Phase 1: Foundation ✅
- [x] Update `UnitScope` to handle NULL unit_id
- [x] Remove debug logging from `UnitScope`
- [x] Implementasi approval workflow
- [x] Add approval columns to `log_penyimpanan_limbah`
- [x] Create `approval_log` table
- [x] Create `ApprovalLog` model
- [x] Update `LogPenyimpananLimbah` model with approval relationships
- [x] Update `LogPenyimpananLimbahController` with approve/reject methods
- [x] Update web routes with approve/reject endpoints
- [x] Update API routes with approve/reject endpoints
- [x] Update `LogPenyimpananController` API with approve/reject methods
- [x] Fix typo `status_aktif` → `status_aktif` (migration)
- [x] Update `PenggunaSistem` model with isSupervisor(), canApproveLogs()
- [x] Make `unit_id` nullable in `pengguna_sistem` table (deferred due to DB constraint)

### Phase 2: Audit Trail ✅
- [x] Create audit_log table migration
- [x] Create AuditLog model
- [x] Create AuditTrait for auto-logging
- [x] Apply trait to all models (13 models total)
- [x] Create AuditLogController
- [x] Add audit log routes
- [ ] Write tests for audit trail

### Phase 3: Sistem Biaya ✅
- [x] Add biaya columns to jenis_limbah table
- [x] Update JenisLimbah model
- [x] Update JenisLimbahController
- [x] Update views for biaya input
- [x] Update JenisLimbahSeeder with sample biaya
- [ ] Write tests for biaya system

### Phase 4: Expiry System Fix ✅
- [x] Fix expiry status enum (Normal → Safe)
- [x] Update expiry calculation logic to match UI
- [x] Verify scheduled task runs correctly
- [ ] Write tests for expiry calculation

### Phase 5: Code Cleanup ✅
- [x] Fix route errors (3 missing routes)
- [x] Fix JenisLimbahSeeder biaya data
- [x] Fix biaya validation and forms


---

## [2026-01-12] Phase Complete: Fix Menu Route Errors

**Files Changed:**
- `routes/web.php` - Added missing route (line 59):
  - `POST /pengangkutan-limbah/bulk-approve` → PengangkutanLimbahController@bulkApprove

**Reasoning:**
- pengangkutan-limbah/index.blade.php referenced non-existent route `pengangkutan-limbah.bulk-approve`
- Route was causing RouteNotFoundException error when accessing the page
- Controller method `bulkApprove()` already exists at line 190 of PengangkutanLimbahController
- View expects POST route with selected_logs array parameter
- Route added to match controller method signature and requirements

**Impact:**
- Pengangkutan Limbah bulk approval page now accessible
- No RouteNotFoundException errors when accessing /pengangkutan-limbah
- Supervisor/Admin can use bulk approve functionality without errors

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Phase Complete: Fix Reports Menu Error

**Files Changed:**
- `app/Http/Controllers/ReportController.php` - Added clearReportCache() method (line 578)
- `routes/web.php` - Added reports.clear-cache route (line 71)

**Reasoning:**
- reports/index.blade.php references non-existent route `reports.clear-cache`
- Controller already had clearReportCache() method at line 578 but file was corrupted showing 574 lines instead of 575
- Added route to match what view expects
- Route flushes all cache and returns success message

**Impact:**
- Reports menu page now accessible without RouteNotFoundException
- All report-related caches can be cleared via Refresh Data button
- No more errors when accessing /reports page

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Fix: Administrator Access Control Issue

**Files Changed:**
- `app/Models/PenggunaSistem.php` - Fixed canAccessUnit() method logic (line 273)

**Reasoning:**
- Regular Administrator was able to access all units because `canAccessUnit()` used `isAdmin()` which returns true for both Super Admin and Administrator
- Only Super Admin should have global access to all units
- Regular Administrator should only access their own unit

**Impact:**
- Fixed: Administrator now can only access their assigned unit
- Fixed: Super Admin still has global access to all units
- Security: Proper separation of access control between Super Admin and Administrator

**Implementation:**
Changed line 273 from:
```php
if ($this->isAdmin()) {
```
To:
```php
if ($this->isSuperAdmin()) {
```

This ensures only Super Admin (with `unit_id = NULL`) can access all units, while Administrator can only access their own unit.

---

## [2026-01-12] Feature: Super Admin with NULL unit_id

**Files Changed:**
- `database/migrations/2026_01_12_042200_make_unit_id_nullable_in_pengguna_sistem_table.php` - New migration
- `database/seeders/PenggunaSistemSeeder.php` - Updated Super Admin to have unit_id = NULL (line 40)
- `app/Http/Controllers/PenggunaSistemController.php` - Updated validation and logic (lines 59-91, 154-191)
- `.env.example` - Added SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD, SUPERADMIN_NAME (lines 69-73)
- `tests/Unit/SuperAdminUnitIdTest.php` - New test suite with 6 tests

**Reasoning:**
- PRD requires Super Admin to have global access without unit assignment
- unit_id = NULL allows Super Admin to bypass UnitScope filtering
- Non-Super Admin must have unit_id for proper organization management
- Migration makes unit_id nullable while maintaining foreign key constraint
- Enforces single Super Admin policy for security

**Impact:**
- Database: unit_id now nullable, existing Super Admin migrated to NULL
- Backend: Updated validation to enforce one-Super-Admin policy
- Frontend: Create/Edit user forms must handle NULL unit_id for Super Admin
- Testing: New tests for Super Admin NULL unit_id behavior
- Security: Enforces single Super Admin rule and proper access control

**Implementation:**
1. Migration makes unit_id nullable with proper foreign key handling
2. Seeder now supports .env config for Super Admin credentials
3. Controller validates:
   - Only one Super Admin allowed
   - Super Admin must have unit_id = NULL
   - Non-Super Admin must have unit_id (required)
   - Non-admin cannot create Super Admin
4. Tests verify all scenarios work correctly
5. Migration down method safely reverts to NOT NULL if needed

---

## [2026-01-12] Fix: Jenis Limbah Form Incomplete Fields

**Files Changed:**
- `resources/views/jenis-limbah/create.blade.php` - Added missing fields (lines 31-93):
  - Kode Limbah (kode_limbah) - Primary key
  - Nama Limbah (nama_limbah) - Required
  - Kemasan (kemasan) - Required
  - Deskripsi Limbah (deskripsi_limbah) - Optional
  - Karakteristik Limbah (karakteristik_id) - Optional dropdown
- `resources/views/jenis-limbah/edit.blade.php` - Added missing kemasan field (lines 62-88):
  - Kemasan (kemasan) - Required input
- `app/Http/Controllers/JenisLimbahController.php` - Added kemasan to validation:
  - Line 42 (store): 'kemasan' => 'required|string|max:255'
  - Line 89 (update): 'kemasan' => 'required|string|max:255'

**Reasoning:**
- User reported create form only showing "Waktu Penyimpanan" and "Status Aktif" fields
- Form was missing critical fields that are in database table and model:
  - Kode Limbah (PRIMARY KEY)
  - Nama Limbah (required field)
  - Kemasan (required field)
  - Deskripsi Limbah (optional but important)
  - Karakteristik Limbah (classification dropdown)
- These fields come BEFORE waktu_penyimpanan_hari and status_aktif in form
- Controller validation was missing 'kemasan' field
- Edit form already had all main fields except kemasan

**Impact:**
- Users can now create Jenis Limbah with complete information
- All required fields are present in create form
- Edit form has complete set of fields including kemasan
- Form matches database schema and model requirements
- Biaya section remains functional below other fields

---

**Last Updated:** 2026-01-12

---

## [2026-01-12] Investigate: Sass Deprecation Warning

**Files Changed:**
- `resources/sass/_variables.scss` - Attempted @forward syntax (reverted)
- `vite.config.js` - No changes made

**Reasoning:**
- `npm run build` shows deprecation warning for `@import 'variables'` in Dart Sass 3.0.0
- Attempted to fix by using `@forward` syntax but this caused build to fail completely
- `@forward` only works for Sass modules with `@mixin` and `@forward`, not for simple variable files
- Reverted to original `@import 'variables'` syntax

**Current Status:**
- Build completes successfully with deprecation warning (non-breaking)
- This is informational warning from Dart Sass 3.0.0
- Code continues to work correctly
- Proper fix requires refactoring to CSS custom properties or other major changes

**Recommendation:**
- Accept deprecation warning for now (non-breaking)
- Consider future refactoring to CSS custom properties if warnings become critical
- Build time: 920ms (acceptable)

**Last Updated:** 2026-01-15

---

## [2026-01-15] Phase 1: Critical System & Security (Settings)

**Files Changed:**
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Providers/AppServiceProvider.php`
- `app/Http/Middleware/CheckMaintenanceMode.php`
- `bootstrap/app.php`
- `database/migrations/2026_01_15_050840_add_settings_columns_to_audit_log_table.php`
- `database/migrations/2026_01_15_051102_make_user_id_nullable_in_audit_log_table.php`
- `app/Models/ApplicationSetting.php`
- `app/Models/AuditLog.php`
- `database/seeders/ApplicationSettingSeeder.php`
- `app/Http/Controllers/SettingsAuditController.php`
- `routes/web.php`

**Reasoning:**
- To implement Phase 1 of WASPRO Settings Plan (Critical System & Security).
- Switched to dynamic, database-backed security settings.
- Implemented settings-based maintenance mode.
- Enhanced audit trail with setting-specific diffs.

**Impact:**
- Configurable login security (attempts, lockout).
- Maintenance mode controllable via settings.
- Comprehensive audit trail for all setting changes.
