## Goal
Fix 500 Internal Server Errors on report pages (`/reports/*` and `/expiry-reports`) caused by the recent Enum refactoring.

## Assumptions
- The root cause is `LogStatus` Enum being treated as a string in Controllers, Views, and Model accessors.
- `LogPenyimpananLimbah` model casts `status_log` to `App\Enums\LogStatus`.
- Laravel 9+ is used (supporting Enums in Eloquent).

## Plan
### 1. Fix Model Accessors
**File:** `app/Models/LogPenyimpananLimbah.php`
- **Change:** Update `getStatusLogBadgeClass` and `getStatusLogText` to match against `LogStatus` Enum cases instead of using `strtoupper($this->status_log)` which causes crashes.
- **Verify:** `php artisan tinker` -> `$log->getStatusLogBadgeClass()` doesn't crash.

### 2. Fix ReportController Collection Filtering
**File:** `app/Http/Controllers/ReportController.php`
- **Change:** In methods `monthly`, `status`, `wasteType`, `company`, `unit`:
    - Replace `$logs->where('status_log', 'Diangkut')` with `$logs->where('status_log', \App\Enums\LogStatus::Diangkut)`.
    - Fix grouping logic if it relies on string keys (Enums as keys might need handling).
    - Ensure cache keys use scalar values if Enums are involved.
- **Verify:** Run `/reports/monthly` and check if aggregation numbers are correct.

### 3. Fix ExpiryReportController
**File:** `app/Http/Controllers/ExpiryReportController.php`
- **Change:** Update `where('status_log', 'Tersimpan')` to use `\App\Enums\LogStatus::Tersimpan`.
- **Verify:** Run `/expiry-reports` without error.

### 4. Fix Blade Views
**Files:**
- `resources/views/reports/monthly.blade.php`
- `resources/views/reports/status.blade.php`
- `resources/views/reports/waste-type.blade.php`
- `resources/views/reports/company.blade.php`
- `resources/views/reports/unit.blade.php`
- `resources/views/expiry-reports/index.blade.php`
- **Change:**
    - Replace `{{ $log->status_log }}` with `{{ $log->status_log->value }}` or `{{ $log->status_log instanceof \App\Enums\LogStatus ? $log->status_log->value : $log->status_log }}`.
    - Replace `strtoupper($log->status_log)` with direct Enum comparison or `->value`.
- **Verify:** Load all report pages in browser (or via test command).

### 5. Verify Fixes
- **Command:** `php artisan test --filter=ReportControllerTest` (if exists) or create a new test.
- **Manual:** Check URLs provided by user.

## Risks & mitigations
- **Risk:** Missed a view or a specific string comparison.
- **Mitigation:** Search for all usages of `status_log` in `resources/views`.
- **Risk:** Cache might hold old data/structure.
- **Mitigation:** Run `php artisan cache:clear` after deploying fixes.

## Rollback plan
- Revert changes to string literals (but this remains broken with Enum casting).
- True rollback would be removing Enum casting from `LogPenyimpananLimbah` (undesirable).
