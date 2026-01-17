# WASPRO Settings Plan Document

**Version:** 1.0.0
**Date:** 2025-01-15
**Focus:** System & Security
**Access Model:** Permission-Based
**Audit Trail:** Brief

---

## 1. Current Settings Analysis

### 1.1 Settings Currently Being Used

#### From `application_settings` table (ApplicationSettingSeeder.php)

| Category | Setting Key | Type | Current Use | Code Location |
|----------|-------------|------|-------------|---------------|
| general | `app.name` | string | ✅ App header/title | ApplicationSetting::get() |
| general | `app.version` | string | ✅ Version tracking | Not actively used |
| general | `app.maintenance_mode` | boolean | ⚠️ Defined but not enforced | No middleware |
| general | `app.timezone` | string | ⚠️ Defined but not used | Config file instead |
| security | `user.max_login_attempts` | integer | ❌ Not implemented | LoginController missing |
| security | `user.lockout_duration` | integer | ❌ Not implemented | LoginController missing |
| security | `user.password_min_length` | integer | ❌ Not enforced | Validation rules hardcoded |
| security | `user.require_email_verification` | boolean | ⚠️ Partially implemented | User model only |
| upload | `upload.max_file_size` | integer | ❌ Not enforced | Hardcoded in requests |
| upload | `upload.allowed_extensions` | json | ❌ Not enforced | Hardcoded in requests |
| notification | `notification.email_enabled` | boolean | ❌ Not implemented | No email sending |
| notification | `notification.admin_email` | string | ❌ Not implemented | No email sending |
| data | `data.pagination_limit` | integer | ❌ Not used | Hardcoded per controller |
| data | `data.export_limit` | integer | ❌ Not implemented | No limit checks |
| data | `data.backup_retention_days` | integer | ❌ Not implemented | No backup system |
| waste | `waste.default_unit` | string | ❌ Not used | Hardcoded as 'kg' |
| waste | `waste.alert_threshold` | integer | ❌ Not used | No capacity alerts |
| waste | `waste.categories` | json | ❌ Not used | JenisLimbah table used |
| report | `report.auto_generate` | boolean | ❌ Not implemented | No scheduled jobs |
| report | `report.default_format` | string | ❌ Not used | User selection only |
| expiry | `critical_days` | integer | ✅ Active | LogPenyimpananLimbah:192 |
| expiry | `warning_days` | integer | ✅ Active | LogPenyimpananLimbah:194 |

#### From `app_settings` table

| Setting Key | Type | Current Use | Code Location |
|-------------|------|-------------|---------------|
| `limbah_expiry_days` | integer | ✅ Active (fallback) | LogPenyimpananLimbah:234 |

### 1.2 Settings NOT Being Used (Dead Settings)

These settings exist in database but are **not referenced** in business logic:

1. **Security Settings (4/4 unused)**
   - `user.max_login_attempts`
   - `user.lockout_duration`
   - `user.password_min_length`
   - `user.require_email_verification`

2. **Upload Settings (2/2 unused)**
   - `upload.max_file_size`
   - `upload.allowed_extensions`

3. **Notification Settings (2/2 unused)**
   - `notification.email_enabled`
   - `notification.admin_email`

4. **Data Settings (3/3 unused)**
   - `data.pagination_limit`
   - `data.export_limit`
   - `data.backup_retention_days`

5. **Waste Settings (3/3 unused)**
   - `waste.default_unit`
   - `waste.alert_threshold`
   - `waste.categories`

6. **Report Settings (2/2 unused)**
   - `report.auto_generate`
   - `report.default_format`

### 1.3 Hardcoded Values That Should Be Settings

| Location | Hardcoded Value | Proposed Setting Key |
|----------|----------------|---------------------|
| FormRequest validations | `max:10240` (10MB) | `upload.max_file_size_kb` |
| FormRequest validations | `['pdf', 'doc', ...]` | `upload.allowed_extensions` |
| Password validation | `min:8` | `security.password_min_length` |
| Pagination | `15` | `data.pagination_per_page` |
| Reports export | No limit | `data.export_max_rows` |
| Waste unit | `'kg'` | `waste.default_unit` |
| Expiry urgent days | `3` | `expiry.urgent_days` |
| Cache TTL | `3600` | `cache.default_ttl_seconds` |
| Login lockout | `5` attempts | `security.max_login_attempts` |
| Login lockout | `15` minutes | `security.lockout_duration_minutes` |

---

## 2. Proposed Functional Settings by Category

### 2.1 Workflow & Approval Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `workflow.approval_required` | `true` | boolean | Require supervisor approval for waste logs | LogPenyimpananLimbahController::store() |
| `workflow.auto_approve_operator` | `false` | boolean | Auto-approve logs from trusted operators | LogPenyimpananLimbahController::store() |
| `workflow.approval_timeout_hours` | `72` | integer | Hours before pending logs auto-reject | Command: PendingLogsCleanup |
| `workflow.allow_rejection_reason` | `true` | boolean | Require reason when rejecting logs | LogPenyimpananLimbahController::approveReject() |
| `workflow.edit_approved_logs` | `false` | boolean | Allow editing already-approved logs | LogPenyimpananLimbahController::update() |
| `workflow.delete_approved_logs` | `false` | boolean | Allow deleting already-approved logs | LogPenyimpananLimbahController::destroy() |

### 2.2 Expiry & Alerts Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `expiry.critical_days` | `7` | integer | Days before expiry shows "Critical" status | LogPenyimpananLimbah::updateExpiryStatus() |
| `expiry.warning_days` | `30` | integer | Days before expiry shows "Warning" status | LogPenyimpananLimbah::updateExpiryStatus() |
| `expiry.urgent_days` | `3` | integer | Days before expiry shows "Urgent" (high priority) | NotificationController::getExpiryNotificationsData() |
| `expiry.default_storage_days` | `90` | integer | Default storage days if not defined in waste type | LogPenyimpananLimbah::calculateExpiryDate() |
| `expiry.check_frequency_hours` | `24` | integer | How often to run expiry status update job | Command: UpdateWasteExpiryStatus |
| `expiry.auto_archive_expired_days` | `365` | integer | Days after expiry to auto-archive logs | Command: ArchiveExpiredWaste |

### 2.3 Notification Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `notification.email_enabled` | `false` | boolean | Enable email notifications | NotificationService::send() |
| `notification.smtp_enabled` | `false` | boolean | Use SMTP for email sending | Mail::send() |
| `notification.admin_email` | `admin@waspro.com` | string | Default admin email for system alerts | NotificationController |
| `notification.superadmin_expiry_alerts` | `true` | boolean | Alert SuperAdmin about expired waste | NotificationController |
| `notification.supervisor_pending_alerts` | `true` | boolean | Alert Supervisor about pending approvals | NotificationController |
| `notification.expiry_check_interval_minutes` | `60` | integer | Frequency of expiry notification checks | ScheduledJob |
| `notification.include_expired` | `true` | boolean | Include already-expired waste in notifications | NotificationController |

### 2.4 Document & Upload Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `upload.max_file_size_kb` | `10240` | integer | Max file upload size in KB (10MB) | FormRequest validation |
| `upload.allowed_extensions` | `["pdf","doc","docx","xls","xlsx","jpg","jpeg","png"]` | json | Allowed file extensions for uploads | FormRequest validation |
| `upload.max_files_per_record` | `5` | integer | Max number of files per waste log | LogPenyimpananLimbahController |
| `upload.storage_path` | `"uploads/waste-documents"` | string | Storage path for uploaded documents | File::store() |
| `upload.require_document_for_transport` | `true` | boolean | Require document when marking as "Diangkut" | LogPenyimpananLimbahController::update() |
| `upload.auto_compress_images` | `true` | boolean | Auto-compress uploaded images | ImageUploadService |

### 2.5 Reports & Scheduling Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `report.default_format` | `"pdf"` | string | Default export format (pdf/excel) | ReportController |
| `report.auto_generate_monthly` | `true` | boolean | Auto-generate monthly reports | ScheduledJob |
| `report.monthly_generation_day` | `1` | integer | Day of month to generate reports | ScheduledJob |
| `report.max_export_rows` | `10000` | integer | Max rows allowed in single export | ReportController |
| `report.include_charts` | `true` | boolean | Include charts in PDF reports | ReportController |
| `report.cache_duration_minutes` | `60` | integer | Cache duration for report data | ReportController |
| `report.proper_format_enabled` | `false` | boolean | Enable PROPER HIJAU format | ReportController (future) |

### 2.6 Operational Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `operational.default_unit` | `"kg"` | string | Default unit for waste measurement | LogPenyimpananLimbah views |
| `operational.capacity_alert_threshold_percent` | `80` | integer | Storage capacity alert threshold | DashboardController |
| `operational.max_logs_per_day` | `100` | integer | Max waste logs per day per unit | LogPenyimpananLimbahController |
| `operational.allow_weekend_transport` | `true` | boolean | Allow transport on weekends | LogPenyimpananLimbahController validation |
| `operational.min_transport_days` | `1` | integer | Minimum days between storage and transport | LogPenyimpananLimbahController validation |
| `operational.auto_assign_code` | `true` | boolean | Auto-generate waste identification codes | LogPenyimpananLimbah::booted() |

### 2.7 System & Security Settings

| Setting Key | Default | Type | Description | Code Usage Location |
|------------|---------|------|-------------|---------------------|
| `system.maintenance_mode` | `false` | boolean | Enable maintenance mode | Middleware |
| `system.timezone` | `"Asia/Jakarta"` | string | Application timezone | Config/app.php |
| `system.cache_enabled` | `true` | boolean | Enable caching | Cache facade |
| `system.cache_ttl_seconds` | `3600` | integer | Default cache TTL in seconds | Cache::remember() |
| `security.password_min_length` | `8` | integer | Minimum password length | Validation rules |
| `security.password_require_uppercase` | `true` | boolean | Require uppercase in password | Validation rules |
| `security.password_require_lowercase` | `true` | boolean | Require lowercase in password | Validation rules |
| `security.password_require_number` | `true` | boolean | Require number in password | Validation rules |
| `security.password_require_special` | `true` | boolean | Require special character in password | Validation rules |
| `security.max_login_attempts` | `5` | integer | Max failed login attempts before lockout | LoginController |
| `security.lockout_duration_minutes` | `15` | integer | Account lockout duration in minutes | LoginController |
| `security.session_timeout_minutes` | `120` | integer | Session timeout duration | Session config |
| `security.require_email_verification` | `true` | boolean | Require email verification for new users | AuthController |
| `security.audit_log_enabled` | `true` | boolean | Enable audit logging | AuditTrait |
| `security.audit_log_retention_days` | `90` | integer | Days to retain audit logs | AuditLog cleanup job |
| `security.ip_whitelist_enabled` | `false` | boolean | Enable IP whitelist for admin access | Middleware |
| `security.ip_whitelist` | `[]` | json | List of allowed IP addresses | Middleware |
| `security.two_factor_enabled` | `false` | boolean | Enable 2FA for admin accounts | AuthController (future) |

---

## 3. Permission-Based Access Matrix

### 3.1 Access by Role per Category

| Category | View | Edit | Create | Delete | Super Admin | Administrator | Supervisor | Management | Operator | Viewer |
|----------|------|------|--------|--------|-------------|---------------|------------|------------|----------|--------|
| **Workflow & Approval** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Expiry & Alerts** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Notification** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Document & Upload** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Reports & Scheduling** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Generate reports | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Operational** | | | | | | | | | | |
| View settings | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **System & Security** | | | | | | | | | | |
| View settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Edit settings | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Security actions* | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

*Security actions include: enabling maintenance mode, changing password requirements, clearing audit logs, managing IP whitelist

### 3.2 Category-Based Permission Codes

```php
// ApplicationSettingPolicy enhancement

public function canAccessCategory(PenggunaSistem $user, string $category): bool
{
    return match($category) {
        'workflow' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement(),
        'expiry' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement(),
        'notification' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement(),
        'document' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement() || $user->isOperator(),
        'report' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement() || $user->isViewer(),
        'operational' => $user->isSuperAdmin() || $user->isAdministrator() || $user->isSupervisor() || $user->isManagement() || $user->isOperator(),
        'system', 'security' => $user->isSuperAdmin(),
        default => false,
    };
}

public function canEditCategory(PenggunaSistem $user, string $category): bool
{
    // Only Super Admin can edit any category
    return $user->isSuperAdmin();
}
```

### 3.3 Setting-Level Granular Permissions

For sensitive settings, implement additional checks:

| Setting Key | Additional Permission Check |
|------------|---------------------------|
| `system.maintenance_mode` | Requires confirmation & reason in audit |
| `security.password_*` | Requires 2FA if enabled |
| `security.max_login_attempts` | Cannot be set below 3 |
| `security.lockout_duration` | Cannot exceed 1440 minutes (24 hours) |
| `expiry.*_days` | Critical must be ≤ Warning |
| `upload.max_file_size_kb` | Cannot exceed 51200 KB (50MB) |
| `operational.max_logs_per_day` | Cannot be below 10 |
| `audit_log_enabled` | Cannot be disabled if audit logs exist |

---

## 4. Audit Trail Implementation

### 4.1 Audit Log Schema Enhancement

```sql
-- Existing columns in audit_log
ALTER TABLE audit_log ADD COLUMN setting_category VARCHAR(50) AFTER table_name;
ALTER TABLE audit_log ADD COLUMN setting_key VARCHAR(255) AFTER setting_category;
ALTER TABLE audit_log ADD COLUMN old_value_text TEXT AFTER old_value;
ALTER TABLE audit_log ADD COLUMN new_value_text TEXT AFTER new_value;
ALTER TABLE audit_log ADD COLUMN ip_address VARCHAR(45) AFTER new_value_text;
ALTER TABLE audit_log ADD COLUMN user_agent TEXT AFTER ip_address;
```

### 4.2 Audit Logging for Settings

```php
// ApplicationSetting model enhancement

protected static function boot()
{
    parent::boot();

    static::updated(function ($setting) {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'application_settings',
            'setting_category' => $setting->category,
            'setting_key' => $setting->key,
            'record_id' => $setting->id,
            'old_value' => ['value' => $setting->getOriginal('value')],
            'new_value' => ['value' => $setting->value],
            'old_value_text' => $setting->getOriginal('value'),
            'new_value_text' => $setting->value,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    });

    static::created(function ($setting) {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'application_settings',
            'setting_category' => $setting->category,
            'setting_key' => $setting->key,
            'record_id' => $setting->id,
            'old_value' => null,
            'new_value' => ['value' => $setting->value],
            'new_value_text' => $setting->value,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    });

    static::deleted(function ($setting) {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'application_settings',
            'setting_category' => $setting->category,
            'setting_key' => $setting->key,
            'record_id' => $setting->id,
            'old_value' => ['value' => $setting->value],
            'new_value' => null,
            'old_value_text' => $setting->value,
            'new_value_text' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    });
}
```

### 4.3 Brief Audit Trail View

For settings, maintain a brief audit trail focused on:
- **Who** changed the setting
- **What** was changed (setting key)
- **When** it was changed
- **From/To** (old value → new value)

```php
// SettingsAuditController

public function index(Request $request)
{
    $logs = AuditLog::with('user')
        ->where('table_name', 'application_settings')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('settings.audit', compact('logs'));
}

public function show($id)
{
    $log = AuditLog::with('user')->findOrFail($id);

    return view('settings.audit-detail', compact('log'));
}
```

### 4.4 Audit Log Retention

- **Default retention:** 90 days (configurable via `security.audit_log_retention_days`)
- **Scheduled job:** `php artisan audit:cleanup --days=90`
- **Critical settings:** Never delete (marked with `is_critical = true`)

### 4.5 Audit Export for Settings

```php
// ExportController

public function exportSettingsAudit(Request $request)
{
    Gate::authorize('export', ApplicationSetting::class);

    $logs = AuditLog::where('table_name', 'application_settings')
        ->orderBy('created_at', 'desc')
        ->get();

    return Excel::download(new SettingsAuditExport($logs), 'settings-audit-'.now()->format('Y-m-d').'.xlsx');
}
```

---

## 5. Implementation Phases

### Phase 1: Critical System & Security (Week 1-2)

**Priority:** HIGH

**Tasks:**
1. ✅ Activate dead security settings
   - Implement `security.password_min_length` in validation
   - Implement `security.max_login_attempts` in LoginController
   - Implement `security.lockout_duration` in LoginController
   - Implement `security.require_email_verification`

2. ✅ Implement maintenance mode middleware
   - Add middleware check for `system.maintenance_mode`
   - Add permission gate for Super Admin only

3. ✅ Implement audit trail for settings
   - Add AuditTrait to ApplicationSetting model
   - Create AuditLog migration enhancements
   - Add brief audit view

**Code Changes:**
- `app/Http/Middleware/MaintenanceMode.php` (new)
- `app/Http/Controllers/Auth/LoginController.php` (enhance)
- `app/Models/ApplicationSetting.php` (add AuditTrait)
- `app/Http/Controllers/SettingsAuditController.php` (new)
- `database/migrations/YYYY_MM_DD_add_audit_columns_to_audit_log.php` (new)

**Testing:**
- Test login lockout after failed attempts
- Test maintenance mode activation
- Test audit log creation on setting changes
- Test settings audit view

---

### Phase 2: Expiry & Alerts (Week 3)

**Priority:** HIGH

**Tasks:**
1. ✅ Add `expiry.urgent_days` setting
2. ✅ Integrate new expiry settings in NotificationController
3. ✅ Create scheduled job for expiry status updates
4. ✅ Implement expiry notification frequency control

**Code Changes:**
- `app/Http/Controllers/NotificationController.php` (enhance)
- `app/Console/Commands/UpdateWasteExpiryStatus.php` (enhance)
- `app/Console/Schedule/ExpiryCheckScheduler.php` (new)
- Database seeder for new settings

**Testing:**
- Test expiry notifications with urgent threshold
- Test scheduled job execution
- Test notification frequency settings

---

### Phase 3: Workflow & Approval (Week 4)

**Priority:** HIGH

**Tasks:**
1. ✅ Add workflow settings to database
2. ✅ Integrate `workflow.approval_required` in LogPenyimpananLimbahController
3. ✅ Implement timeout for pending approvals
4. ✅ Add rejection reason requirement

**Code Changes:**
- `app/Http/Controllers/LogPenyimpananLimbahController.php` (enhance)
- `app/Console/Commands/CleanupPendingLogs.php` (new)
- `app/Http/Requests/LogPenyimpananLimbahRequest.php` (enhance)
- Views for approval workflow settings

**Testing:**
- Test approval requirement logic
- Test pending log timeout
- Test rejection reason validation

---

### Phase 4: Document & Upload (Week 5)

**Priority:** MEDIUM

**Tasks:**
1. ✅ Activate upload settings
2. ✅ Implement file size validation using setting
3. ✅ Implement allowed extensions validation
4. ✅ Add max files per record validation

**Code Changes:**
- `app/Http/Requests/UploadDocumentRequest.php` (enhance)
- `app/Http/Controllers/LogPenyimpananLimbahController.php` (enhance)
- `app/Services/FileUploadService.php` (new)

**Testing:**
- Test file upload with size limits
- Test file extension validation
- Test max files per record

---

### Phase 5: Reports & Scheduling (Week 6)

**Priority:** MEDIUM

**Tasks:**
1. ✅ Activate report settings
2. ✅ Implement max export rows limit
3. ✅ Implement report caching with TTL
4. ✅ Add scheduled monthly report generation

**Code Changes:**
- `app/Http/Controllers/ReportController.php` (enhance)
- `app/Console/Schedule/MonthlyReportGenerator.php` (new)
- `app/Exports/*Export.php` (enhance for row limits)

**Testing:**
- Test export row limits
- Test report caching
- Test scheduled monthly reports

---

### Phase 6: Operational Settings (Week 7)

**Priority:** LOW

**Tasks:**
1. ✅ Add operational settings
2. ✅ Implement capacity alert threshold
3. ✅ Add max logs per day validation
4. ✅ Implement weekend transport rules

**Code Changes:**
- `app/Http/Controllers/DashboardController.php` (enhance)
- `app/Http/Controllers/LogPenyimpananLimbahController.php` (enhance)
- Validation rules for operational settings

**Testing:**
- Test capacity alerts
- Test max logs per day
- Test weekend transport rules

---

### Phase 7: Notification System (Week 8)

**Priority:** MEDIUM

**Tasks:**
1. ✅ Implement email notification service
2. ✅ Activate notification settings
3. ✅ Add SMTP configuration
4. ✅ Implement notification frequency control

**Code Changes:**
- `app/Services/NotificationService.php` (new)
- `app/Http/Controllers/NotificationController.php` (enhance)
- `config/mail.php` (enhance)

**Testing:**
- Test email notifications
- Test SMTP configuration
- Test notification frequency

---

### Phase 8: Advanced Security (Week 9)

**Priority:** LOW

**Tasks:**
1. ✅ Implement password complexity rules
2. ✅ Add session timeout
3. ✅ Implement IP whitelist (optional)
4. ✅ Prepare for 2FA implementation

**Code Changes:**
- `app/Http/Middleware/CheckSessionTimeout.php` (new)
- `app/Http/Middleware/CheckIpWhitelist.php` (new)
- `app/Http/Controllers/Auth/LoginController.php` (enhance)
- Validation rules for password complexity

**Testing:**
- Test password complexity rules
- Test session timeout
- Test IP whitelist

---

## 6. Migration Strategy

### 6.1 Database Migration Plan

```sql
-- Step 1: Add new columns to application_settings (if needed)
ALTER TABLE application_settings ADD COLUMN IF NOT EXISTS sensitive BOOLEAN DEFAULT FALSE;

-- Step 2: Add audit log enhancements
ALTER TABLE audit_log ADD COLUMN setting_category VARCHAR(50) AFTER table_name;
ALTER TABLE audit_log ADD COLUMN setting_key VARCHAR(255) AFTER setting_category;
ALTER TABLE audit_log ADD COLUMN old_value_text TEXT AFTER old_value;
ALTER TABLE audit_log ADD COLUMN new_value_text TEXT AFTER new_value;
ALTER TABLE audit_log ADD COLUMN ip_address VARCHAR(45) AFTER new_value_text;
ALTER TABLE audit_log ADD COLUMN user_agent TEXT AFTER ip_address;

-- Step 3: Create index for faster audit queries
CREATE INDEX idx_audit_table_name ON audit_log(table_name);
CREATE INDEX idx_audit_created_at ON audit_log(created_at);
CREATE INDEX idx_audit_setting_key ON audit_log(setting_key);
```

### 6.2 Settings Migration Script

```php
// Migrate existing hard-coded values to settings

public function up()
{
    $settings = [
        [
            'key' => 'security.password_min_length',
            'value' => '8',
            'type' => 'integer',
            'category' => 'security',
            'description' => 'Minimum password length',
            'is_active' => true,
        ],
        [
            'key' => 'security.max_login_attempts',
            'value' => '5',
            'type' => 'integer',
            'category' => 'security',
            'description' => 'Max failed login attempts before lockout',
            'is_active' => true,
        ],
        [
            'key' => 'security.lockout_duration_minutes',
            'value' => '15',
            'type' => 'integer',
            'category' => 'security',
            'description' => 'Account lockout duration in minutes',
            'is_active' => true,
        ],
        // Add all other proposed settings
    ];

    foreach ($settings as $setting) {
        ApplicationSetting::updateOrCreate(
            ['key' => $setting['key']],
            $setting
        );
    }
}
```

### 6.3 Rollback Strategy

For each phase, maintain database migrations with down() methods:

```php
public function down()
{
    // Remove new settings
    ApplicationSetting::whereIn('key', $this->newSettingKeys)->delete();

    // Remove audit log columns
    Schema::table('audit_log', function (Blueprint $table) {
        $table->dropColumn([
            'setting_category',
            'setting_key',
            'old_value_text',
            'new_value_text',
            'ip_address',
            'user_agent',
        ]);
    });
}
```

---

## 7. Settings Management UI

### 7.1 Settings Dashboard Structure

```
/settings
├── /dashboard
│   ├── Overview of all categories
│   ├── Quick status indicators
│   └── Recent audit logs
├── /workflow
│   ├── List of workflow settings
│   ├── Edit form
│   └── Audit log
├── /expiry
│   ├── List of expiry settings
│   ├── Edit form
│   └── Audit log
├── /notification
│   ├── List of notification settings
│   ├── Edit form
│   └── Audit log
├── /document
│   ├── List of document settings
│   ├── Edit form
│   └── Audit log
├── /report
│   ├── List of report settings
│   ├── Edit form
│   └── Audit log
├── /operational
│   ├── List of operational settings
│   ├── Edit form
│   └── Audit log
├── /system
│   ├── List of system settings
│   ├── Edit form
│   └── Audit log
├── /security
│   ├── List of security settings
│   ├── Edit form
│   └── Audit log
└── /audit
    ├── Full audit log
    ├── Filter by category/setting
    └── Export audit log
```

### 7.2 Settings Form Components

```php
// Settings form view (example)

@foreach ($settings as $setting)
<div class="setting-item" data-category="{{ $setting->category }}">
    <div class="setting-label">
        <label>{{ $setting->description }}</label>
        <small class="text-muted">{{ $setting->key }}</small>
    </div>

    <div class="setting-input">
        @if($setting->type === 'boolean')
            <input type="checkbox" name="value" {{ $setting->value ? 'checked' : '' }}>
        @elseif($setting->type === 'integer')
            <input type="number" name="value" value="{{ $setting->value }}">
        @elseif($setting->type === 'json')
            <textarea name="value">{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</textarea>
        @else
            <input type="text" name="value" value="{{ $setting->value }}">
        @endif
    </div>

    @if($setting->category === 'security')
    <div class="security-warning">
        <i class="fas fa-shield-alt"></i>
        <span>Security setting - requires confirmation</span>
    </div>
    @endif

    <button class="btn-audit" data-setting-id="{{ $setting->id }}">
        <i class="fas fa-history"></i> View Audit
    </button>
</div>
@endforeach
```

---

## 8. Testing Strategy

### 8.1 Unit Tests

```php
// tests/Unit/ApplicationSettingTest.php

public function test_setting_enforces_max_login_attempts()
{
    ApplicationSetting::set('security.max_login_attempts', 3);

    // Attempt 5 logins
    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', ['email' => 'test@test.com', 'password' => 'wrong']);
    }

    // Should be locked out after 3
    $user = PenggunaSistem::where('email', 'test@test.com')->first();
    $this->assertTrue($user->is_locked_out);
}

public function test_setting_audit_log_created()
{
    $setting = ApplicationSetting::create([
        'key' => 'test.setting',
        'value' => 'old_value',
        'type' => 'string',
        'category' => 'test',
    ]);

    $setting->update(['value' => 'new_value']);

    $audit = AuditLog::where('setting_key', 'test.setting')->first();
    $this->assertNotNull($audit);
    $this->assertEquals('old_value', $audit->old_value_text);
    $this->assertEquals('new_value', $audit->new_value_text);
}
```

### 8.2 Integration Tests

```php
// tests/Feature/SettingsWorkflowTest.php

public function test_approval_required_setting_affects_log_creation()
{
    ApplicationSetting::set('workflow.approval_required', true);

    $user = $this->createOperatorUser();
    $this->actingAs($user)
        ->post('/log-penyimpanan', $this->validLogData());

    $log = LogPenyimpananLimbah::first();
    $this->assertEquals('pending', $log->approval_status);
}
```

### 8.3 Security Tests

```php
// tests/Feature/SettingsSecurityTest.php

public function test_only_super_admin_can_edit_security_settings()
{
    $admin = $this->createAdministratorUser();
    $this->actingAs($admin)
        ->post('/settings/security/update', ['password_min_length' => 4])
        ->assertStatus(403);
}

public function test_maintenance_mode_blocks_non_admin_access()
{
    ApplicationSetting::set('system.maintenance_mode', true);

    $this->get('/dashboard')
        ->assertStatus(503);

    $this->actingAs($this->createSuperAdmin())
        ->get('/dashboard')
        ->assertStatus(200);
}
```

---

## 9. Documentation

### 9.1 Settings Documentation Page

Create `docs/settings.md` with:
- Complete list of all settings
- Default values
- Allowed ranges/values
- Business logic affected
- Permission requirements
- Related code locations

### 9.2 API Documentation for Settings

Add to Scribe/OpenAPI:
```yaml
/settings:
  get:
    summary: List all settings by category
    security:
      - bearerAuth: []
    parameters:
      - name: category
        in: query
        schema:
          type: string
          enum: [workflow, expiry, notification, document, report, operational, system, security]

/settings/{key}:
  put:
    summary: Update a setting value
    security:
      - bearerAuth: []
    requestBody:
      content:
        application/json:
          schema:
            type: object
            properties:
              value:
                type: string
              reason:
                type: string
                description: Required for security settings

/settings/{key}/audit:
  get:
    summary: Get audit log for a setting
    security:
      - bearerAuth: []
```

---

## 10. Summary Statistics

| Metric | Count |
|--------|-------|
| **Total Proposed Settings** | 60 |
| **Currently Active** | 2 |
| **Currently Unused** | 16 |
| **New Settings** | 42 |
| **Security Settings** | 15 |
| **Workflow Settings** | 6 |
| **Expiry Settings** | 6 |
| **Notification Settings** | 7 |
| **Document Settings** | 7 |
| **Report Settings** | 7 |
| **Operational Settings** | 6 |
| **System Settings** | 6 |
| **Implementation Phases** | 8 |
| **Estimated Duration** | 9 weeks |
| **Estimated Testing Effort** | 2 weeks |

---

## 11. Risk Assessment

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Breaking existing functionality | Medium | High | Thorough testing, gradual rollout |
| Performance impact from audit logging | Low | Medium | Implement cleanup jobs, indexing |
| Incorrect setting values causing issues | Medium | High | Validation rules, min/max constraints |
| Permission bypass vulnerabilities | Low | Critical | Comprehensive security testing |
| Data migration issues | Low | Medium | Backup before migration, rollback plan |

---

## 12. Success Criteria

The settings implementation will be considered successful when:

1. ✅ All 60 proposed settings are functional
2. ✅ Permission-based access is enforced for all settings
3. ✅ Audit trail is brief but complete for all setting changes
4. ✅ No hardcoded values remain in production code
5. ✅ Settings can be managed via UI without code changes
6. ✅ Audit log retention is automated
7. ✅ All phases completed within estimated timeline
8. ✅ All security tests pass
9. ✅ Performance benchmarks are met
10. ✅ Documentation is complete and accurate

---

**Document Status:** ✅ Complete
**Ready for Review:** ✅ Yes
**Next Steps:** Begin Phase 1 implementation
