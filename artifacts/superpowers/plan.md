# Plan: Fix GitHub API Test Failure (Phase 2)

## Goal
Resolve the continuing "API Tests" failure in GitHub Actions.
**Root Cause Hypothesis**: The `ApiTestCase.php` file defines a **hardcoded database schema** (`refreshTestingSchema`) instead of using Laravel's standard `RefreshDatabase` (which runs actual migrations). This hardcoded schema is likely out-of-sync with recent changes (e.g., `kategori_id` or other fields), causing tests to fail with "Column not found" errors in the CI environment (SQLite).

## Proposed Changes
### Tests
#### [MODIFY] [tests/Feature/Api/ApiTestCase.php](file:///home/john-d/Documents/waspro/tests/Feature/Api/ApiTestCase.php)
- Compare the `Schema::create` definitions in this file against the actual `database/migrations` files.
- Add any missing columns or tables (e.g., ensure `jenis_limbah`, `unit_pembangkit`, etc., match model expectations).
- Alternatively, if feasible, refactor to use `use RefreshDatabase;` and delete the manual schema logic to preventing future desyncs (preferred if tests are standard).

## Verification
1.  **Reproduction**:
    - Run `php artisan test --testsuite=Feature --filter=Api` locally.
    - If it passes locally with MySQL but fails in CI, force SQLite execution locally:
      ```bash
      DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=Api
      ```
    - Confirm this fails locally (reproducing the CI issue).
2.  **Fix Verification**:
    - Apply schema updates.
    - Run the SQLite command again.
    - Result must be GREEN.

## Dependencies
None.
