# Brainstorm: GitHub API Test Failure

## Goal
Resolve the "API Tests / test (push)" failure reported on GitHub Actions.

## Constraints
- **Environment**: GitHub Actions uses `ubuntu-latest`, PHP 8.3, `sqlite` (memory), and a fresh `.env` from `.env.example`.
- **Access**: We cannot see the *exact* GitHub error log from the screenshot (it only shows "Failing"), so we must infer or reproduce it.
- **Time**: User reported failure "after 18s", suggesting a quick failure (bootstrap or early test).

## Known context
- **Workflow**: `.github/workflows/api-tests.yml` runs `php artisan test --testsuite=Feature --filter=Api`.
- **Tests**: There are 5 API test files in `tests/Feature/Api/`.
- **Local Status**: Local execution attempts returned empty output (indeterministic), but the codebase recently underwent significant changes (DashboardService).
- **Recent Changes**: Refactored `DashboardService::getUnits` (Collection return) and `isSuperAdmin` key. These changes *should not* affect API tests unless they rely on DashboardService (unlikely for `JenisLimbahApiTest`).
- **Potential Cause**: The GitHub workflow uses `.env.example`. If `.env.example` has missing keys or invalid defaults compared to the local `.env`, tests will fail.

## Risks
- **Ignored Regressions**: Disabling the test hides actual API bugs.
- **False Positives**: The test might be verifying a feature not fully configured in the `.env.example`.
- **Deployment Block**: If the repo has branch protection rules, this failure prevents merging.

## Options
1.  **Debug & Fix (Recommended)**:
    - Attempt to reproduce locally with `APP_ENV=testing` and `.env.example` settings.
    - If reproducible, fix the code or the test.
    - If environment related, update `.env.example`.
2.  **Disable Workflow (Temporary)**:
    - Comment out or delete `.github/workflows/api-tests.yml`.
    - **Pros**: Unblocks deployment immediately.
    - **Cons**: Loses CI coverage for APIs.
3.  **Ignore**:
    - Tell user it's fine if they don't use API features.
    - **Cons**: Bad practice; leaves "red" CI status.

## Recommendation
**Option 1 (Debug & Fix)**.
We should try to run the tests locally with the *exact* command used in CI to identify the error. If it's a simple configuration miss (e.g. missing API key in `.env.example`), it's an easy fix.

## Acceptance criteria
- The `php artisan test --testsuite=Feature --filter=Api` command passes locally.
- (Implicit) The Fix is pushed and GitHub Actions turns Green.
