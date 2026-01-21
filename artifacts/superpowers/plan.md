# Plan: Fix CSP Errors (Dev-Aware + CDNs)

## Goal
Resolve console errors caused by strict Content Security Policy (CSP) blocking external libraries (jQuery, AOS, Chart.js) and Vite's Hot Module Replacement (HMR) during local development.

## Assumptions
- Environment is Local/Dev (`app()->isLocal()` is true).
- Vite runs on default port `5173`.
- External libraries identified from logs: jQuery, AOS (unpkg), Chart.js (jsdelivr), Google Fonts.

## Plan

### Phase 1: Update CSP Middleware
#### [MODIFY] [app/Http/Middleware/ContentSecurityPolicy.php](file:///home/john-d/Documents/waspro/app/Http/Middleware/ContentSecurityPolicy.php)
-   Refactor `handle` method to build the CSP string dynamically.
-   **Base Policy**: Allow `self`, `data:`, `https://ui-avatars.com`.
-   **CDNs**: Add:
    -   `https://code.jquery.com`
    -   `https://cdn.jsdelivr.net`
    -   `https://unpkg.com`
    -   `https://fonts.googleapis.com` (Style)
    -   `https://fonts.gstatic.com` (Font)
-   **Dev-Specific**: If `app()->isLocal()`, add:
    -   `http://localhost:5173`
    -   `http://[::1]:5173` (IPv6 localhost)
    -   `ws://localhost:5173` (Websockets for HMR)
    -   `wss://localhost:5173`

### Phase 2: Verification
#### [VERIFY]
-   Run `php -l app/Http/Middleware/ContentSecurityPolicy.php` to ensure no syntax errors.
-   (Manual) User loads Dashboard in browser:
    -   Console should be clear of redness.
    -   Vite "connected" message should appear (or at least not fail silent).
    -   Charts and Animations (AOS) should work.

## Risks & mitigations
-   **Security**: Whitelisting `unpkg.com` and `jsdelivr.net` is broad.
    -   *Mitigation*: Necessary trade-off for using CDN-based architecture. In production build, we might want to narrow this if possible, but for now, functionality is priority.
-   **Vite Port**: If user runs Vite on a different port (e.g. 5174), it will still block.
    -   *Mitigation*: Add common ranges or sticky port if needed. For now 5173 is standard.

## Rollback plan
-   Revert `app/Http/Middleware/ContentSecurityPolicy.php` to previous state (or purely comment out header setting).
