# Plan: Final Polish (Minors & Nits)

## Goal
Address the remaining 2 Minor and 2 Nit issues from the Codebase Review to achieve a perfect score and easier maintainability.

## Assumptions
- `ApprovalStatus` Enum will cover: Pending (`pending`), Approved (`approved`), Rejected (`rejected`).
- New controller will be named `LogPenyimpananApprovalController` to keep it focused.

## Plan

### Phase 1: Enum Rollout (Minor 1)
#### [NEW] [app/Enums/ApprovalStatus.php](file:///home/john-d/Documents/waspro/app/Enums/ApprovalStatus.php)
-   Create backed string Enum: `Pending`, `Approved`, `Rejected`.

#### [MODIFY] [app/Models/LogPenyimpananLimbah.php](file:///home/john-d/Documents/waspro/app/Models/LogPenyimpananLimbah.php)
-   Add cast: `'approval_status' => \App\Enums\ApprovalStatus::class`.

### Phase 2: Logic Extraction (Minor 2)
#### [NEW] [app/Http/Controllers/LogPenyimpananApprovalController.php](file:///home/john-d/Documents/waspro/app/Http/Controllers/LogPenyimpananApprovalController.php)
-   Create controller.
-   Move `approve`, `reject`, `bulkApprove` methods here from `LogPenyimpananLimbahController`.
-   Use `ApprovalStatus` Enum in logic.
-   Add Strict Return Types immediately (Addressing Nit 1).

#### [MODIFY] [app/Http/Controllers/LogPenyimpananLimbahController.php](file:///home/john-d/Documents/waspro/app/Http/Controllers/LogPenyimpananLimbahController.php)
-   Remove moved methods.
-   Add Strict Return Types to remaining methods (Addressing Nit 1).

#### [MODIFY] [routes/web.php](file:///home/john-d/Documents/waspro/routes/web.php)
-   Update approval routes to point to `LogPenyimpananApprovalController`.

### Phase 3: CSP Config (Nit 2)
#### [NEW] [config/csp.php](file:///home/john-d/Documents/waspro/config/csp.php)
-   Define `scripts`, `styles`, `fonts`, `images`, `connect` arrays here.

#### [MODIFY] [app/Http/Middleware/ContentSecurityPolicy.php](file:///home/john-d/Documents/waspro/app/Http/Middleware/ContentSecurityPolicy.php)
-   Refactor to pull values from `config('csp')`.

### Phase 4: Verification
#### [VERIFY]
-   Run `php artisan route:list | grep log-penyimpanan` to verify routes.
-   Run `php artisan test --filter=LogPenyimpanan` to ensure no regressions in logic.
-   Manual: Check Dashboard (CSP) and Approval Flow.

## Risks & mitigations
-   **Risk**: Breaking approval flow if Enum casting conflicts with existing string data in DB.
    -   *Mitigation*: Ensure Enum values match DB strings exactly (`approved`, `rejected` - lowercase).
-   **Risk**: Route cache.
    -   *Mitigation*: Run `php artisan route:clear`.

## Rollback plan
-   Revert changes using git (or restore files from backup if manual).
