# Plan: Fix GitHub API Test Failure

## Goal
Resolve the "API Tests / test (push)" failure in GitHub Actions by identifying and fixing the environment mismatch between local development and CI.

## Proposed Changes
### Configuration
#### [MODIFY] [.env.example](file:///home/john-d/Documents/waspro/.env.example)
- Update to include missing keys or correct defaults required for API tests to pass (e.g., `APP_KEY`, specific database configs, or third-party service mocks).

### Tests
#### [MODIFY] [tests/Feature/Api/JenisLimbahApiTest.php](file:///home/john-d/Documents/waspro/tests/Feature/Api/JenisLimbahApiTest.php) (If needed)
- Robustness improvements if tests are flaky.

## Verification
1.  **Simulation**:
    - Backup current `.env`.
    - Copy `.env.example` to `.env`.
    - Run `php artisan key:generate` (mimic CI).
    - Run `php artisan test --testsuite=Feature --filter=Api`.
2.  **Success Criteria**:
    - The tests must pass using the *example* environment configuration.
3.  **Restoration**:
    - Restore original `.env` after verification.

## Dependencies
None.
