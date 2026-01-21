# Superpowers Brainstorm

## Task
Brainstorm for this task: **Fix "many errors" (CSP violations) reported by user after recent Content Security Policy implementation.**

## Goal
Eliminate console errors and restore application functionality by refining the `ContentSecurityPolicy` middleware to whitelisting necessary external resources (CDNs, Vite) while maintaining security best practices.

## Constraints
- **Security**: Do not remove CSP entirely; refine it.
- **Environment**: Must support local development (Vite HMR often uses port 5173 and websockets).
- **Legitimate Resources**: Must allow libraries currently used in Blade/JS (likely jQuery, Chart.js, AOS, Google Fonts).

## Known context
- Recently added `ContentSecurityPolicy` middleware.
- User screenshot (likely) shows blocked resources.
- Application uses:
    - Laravel 11/12
    - Vite (for asset bundling/serving)
    - Older libraries likely via CDN (jQuery, AOS, Chart.js).

## Risks
- **Broken UI**: If we miss a font or style CDN, the UI looks broken.
- **Broken Interactivity**: If we miss a script CDN (like jQuery or Alpine), buttons/modals won't work.
- **Development Friction**: If Vite HMR is blocked, developer experience suffers.

## Options
1.  **Relaxed Policy (Whitelist All Common CDNs)**: Add `*.jquery.com`, `*.jsdelivr.net`, `unpkg.com`, `*.googleapis.com`, `*.gstatic.com` to appropriate directives.
2.  **Dev-Aware Policy**: Explicitly check `app()->isLocal()` or `config('app.debug')` and allow `ws:` and `connect-src` for Vite (`localhost:5173`, `[::1]:5173`) specifically in dev mode.
3.  **Report-Only Mode**: Switch to `Content-Security-Policy-Report-Only` header temporarily to stop blocking while logging errors.

## Recommendation
**Option 2 (Dev-Aware + Whitelist)**.
This addresses the root causes:
1.  **Vite**: Allow `http://localhost:5173`, `http://[::1]:5173`, and `ws://localhost:5173` for `connect-src`, `script-src`, `style-src`.
2.  **CDNs**: Broaden the whitelist to include `code.jquery.com`, `unpkg.com` (often used for AOS/libraries), and ensure `cdn.jsdelivr.net` is allowed for styles/scripts.
3.  **Fonts**: Ensure `fonts.gstatic.com` and `fonts.googleapis.com` are allowed.

## Acceptance criteria
- [ ] User confirms console errors are gone.
- [ ] Styles and Scripts load correctly.
- [ ] Vite HMR works in development.
