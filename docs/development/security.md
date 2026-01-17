# 🔒 Security Development Notes

## Overview
This document tracks all security-related changes and considerations for the WASPRO application.

---

## [2026-01-11] Initial Documentation Setup

**Files Created:**
- `docs/development/security.md` - This file

**Description:**
- Created security development notes tracking
- Established structure for documenting security changes

---

## Current Security Implementation

### Authentication
- **Framework:** Laravel's built-in authentication system
- **Session Driver:** Database (`SESSION_DRIVER=database`)
- **Password Hashing:** Bcrypt with 12 rounds (`BCRYPT_ROUNDS=12`)
- **Remember Tokens:** Supported via `CanResetPassword` trait
- **Email Verification:** Implemented via `MustVerifyEmail` interface

### Authorization
- **Role-Based Access Control (RBAC):**
  - Roles: Super Admin, Administrator, Supervisor, Operator, Viewer
  - Table: `pengguna_peran` (many-to-many relationship)
- **Unit-Based Scope:**
  - `UnitScope` filters data by `unit_id`
  - Super Admin sees all units
  - Other users see only their unit's data
- **Middleware:**
  - `auth` - Authentication required
  - `auth:sanctum` - API authentication
  - Custom middleware for role checks (if any)

### Input Validation
- **Form Request Classes:** Laravel validation
- **Mass Assignment Protection:** `$fillable` property on models
- **CSRF Protection:** Enabled globally
- **XSS Protection:** Blade automatically escapes output

### API Security
- **CORS Configuration:**
  - Allowed origins: `http://localhost:8000,http://localhost:3000`
  - Supports credentials: `true`
- **Rate Limiting:**
  - API limit: 120 requests per 60 seconds
- **API Authentication:**
  - Laravel Sanctum for token-based auth
  - Session-based auth for web

### Data Protection
- **Password Storage:** Bcrypt hashing
- **Sensitive Data:** Not logged (environment variables excluded)
- **File Uploads:**
  - Validation on MIME type and size
  - Stored in `storage/app/public/`
  - Sanitized filenames

---

## Security Guidelines for Development

### 1. Never Trust User Input
- Always validate input on server side
- Use prepared statements (Eloquent handles this)
- Sanitize file uploads

### 2. Protect Against Common Vulnerabilities
- **SQL Injection:** Use Eloquent ORM / Query Builder
- **XSS:** Blade automatically escapes `{{ }}` output
- **CSRF:** Include `@csrf` in all forms
- **File Upload:** Validate MIME type, size, and content

### 3. Authentication & Session Security
- Use HTTPS in production
- Set `SESSION_SECURE_COOKIE=true` in production
- Set `SESSION_SAME_SITE=lax` or `strict`
- Implement rate limiting on login attempts

### 4. Authorization
- Always check user permissions
- Use middleware for route protection
- Implement row-level security (UnitScope)
- Never rely on client-side authorization

### 5. API Security
- Implement proper CORS configuration
- Use rate limiting
- Validate and sanitize all API inputs
- Use Sanctum tokens for API authentication

### 6. Data Protection
- Never commit secrets (`.env`, credentials)
- Use environment variables for sensitive config
- Implement proper password policies
- Encrypt sensitive data at rest

---

## Change Log Template

```markdown
### [YYYY-MM-DD] [Type] Description

**Files Changed:**
- `path/to/file.php` - Description (line X)
- `path/to/another.php` - Description (line Y)

**Vulnerability Addressed:**
- CVE-XXXX-XXXX or description

**Solution:**
- How the issue was fixed

**Testing:**
- How this was tested
```

---

## Security Checklist

Before deploying changes:
- [ ] All user inputs validated
- [ ] SQL injection protection in place
- [ ] XSS protection enabled
- [ ] CSRF tokens included
- [ ] File uploads validated
- [ ] Authentication working
- [ ] Authorization checks implemented
- [ ] No secrets in code
- [ ] HTTPS enabled (production)
- [ ] Rate limiting configured

---

## Known Security Considerations

### Unit Scope Implementation
- Currently only applies to `LogPenyimpananLimbah`
- Other models need scope implementation
- See `docs/development/backend.md` for progress

### Offline Sync
- Client UUID tracking implemented
- Need to validate client identities
- Consider adding client certificates

---

**Last Updated:** 2026-01-11
