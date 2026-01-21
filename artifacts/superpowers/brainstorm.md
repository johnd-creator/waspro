# Superpowers Brainstorm

## Goal
Restore the "Super Admin Unit Filter" on the Dashboard which disappeared after recent optimizations, while maintaining the performance improvements (avoiding OOM).

## Constraints
- **Performance**: Must not re-introduce memory leaks or OOM issues. The unit list is small, so fetching it is cheap, but we must ensure we don't accidentally load heavy relationships.
- **Role-based**: Only Super Admin should see the filter.
- **UX**: The filter should look and work as it did before (dropdown selection).

## Known context
- **Bug Location**: `app/Services/DashboardService.php` vs `resources/views/dashboard/index.blade.php`.
- **Root Cause**: `DashboardService::getUnits()` was changed to return an **array** (using `->toArray()`), but the Blade view expects a **Collection of Objects** (accessing properties via `$unit->unit_id`).
- **Impact**: PHP likely throws a TypeError or Warning when accessing property on array, causing the view to fail or the section to be skipped if error suppression is active (though usually this results in a 500 error). User reports "filter is gone", implying the UI element is missing.
- **Current State**: Backend logic for filtering `buildLogQuery` is correctly implemented and respects `unit_id`.

## Risks
- **Data Type Mismatch**: Fixing the view to use array syntax `['unit_id']` works but is less "Laravel-like".
- **Service Consistency**: Changing the service to return Objects might affect other consumers if they expect arrays (though currently only Controller uses it for the View).

## Options (2–4)
1.  **Revert to Returning Collection (Recommended)**: Modify `DashboardService::getUnits()` to remove `->toArray()`. This restores compatibility with the existing Blade view (`$unit->unit_id`). Memory impact is negligible for a list of units.
2.  **Update View to Array Syntax**: Modify `dashboard/index.blade.php` to use `$unit['unit_id']`. This accepts the "optimized" array return but requires changing the frontend code.
3.  **AJAX Loading**: Load the units list via a separate API call. (Overkill, usage is simple).

## Recommendation
**Option 1: Revert to Returning Collection**.
It is the standard Laravel way to pass Eloquent Collections to views. The memory overhead of a few dozen Unit objects is trivial compared to the thousands of Log objects that caused the OOM. The OOM fix was primarily about *Logs* (thousands of rows), not *Units* (reference data).

## Acceptance criteria
- [ ] `DashboardService::getUnits()` returns a Collection (or array of objects).
- [ ] Dashboard View renders the Unit Filter dropdown for Super Admin.
- [ ] Selecting a unit and submitting the form filters the dashboard data correctly (Log counts update).
- [ ] No 500 errors on Dashboard load.
