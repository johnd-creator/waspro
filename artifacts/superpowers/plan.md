# Plan: Restore Dashboard Filter

## Goal
Restore the "Super Admin Unit Filter" on the Dashboard which failed validation due to:
1.  Data Type Mismatch (Array vs Object) - **Fixed**.
2.  Variable Name Mismatch (`is_super_admin` vs `$isSuperAdmin`) - **Pending**.

## Proposed Changes
### Backend
#### [MODIFY] [DashboardService.php](file:///home/john-d/Documents/waspro/app/Services/DashboardService.php)
- Method: `getUnits()`
    - (Done) Remove `->toArray()` to return an Eloquent Collection.
- Method: `getDashboardData(array $filters): array`
    - Change key `'is_super_admin'` to `'isSuperAdmin'` to match blade variable naming convention.

## Verification
1.  **Manual Verification**:
    - Open Dashboard `/dashboard` as Super Admin.
    - Verify the "Unit Filter" dropdown appears.
    - Verify `isset($isSuperAdmin)` passes in View.
2.  **Code Verification**:
    - Created `verify_dashboard_units.php` (for Step 1).
    - Will verify key change via code inspection.

## Dependencies
None.
