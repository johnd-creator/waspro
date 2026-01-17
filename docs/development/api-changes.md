# 🔄 API Changes Log

## Overview
This document tracks all API changes made to the WASPRO backend. This is for internal tracking - see `docs/api/mobile-guide.md` for Flutter developer documentation.

---

## [2026-01-11] Initial Documentation Setup

**Files Created:**
- `docs/development/api-changes.md` - This file

**Description:**
- Created API changes log for internal tracking

---

## Change Log Template

```markdown
### [YYYY-MM-DD] [Type] Description

**Endpoints Changed:**
- `METHOD /api/endpoint` - Description

**Files Changed:**
- `routes/api.php` - Route updated (line X)
- `app/Http/Controllers/ExampleController.php` - Controller updated (line Y)

**Breaking Change:** Yes/No

**Request Changes:**
- Old: `...`
- New: `...`

**Response Changes:**
- Old: `...`
- New: `...`

**Impact on Flutter App:**
- Update needed: Yes/No
- Migration steps:
  1. ...
  2. ...

**OpenAPI Spec Updated:** Yes/No
**Postman Collection Updated:** Yes/No
```

---

## Current API Status

### Implemented Endpoints
- Authentication (`/api/login`, `/api/logout`, `/api/me`)
- Dashboard (`/api/dashboard`)
- Waste Logs (`/api/log-penyimpanan`)
- Waste Types (`/api/jenis-limbah`)
- Companies (`/api/perusahaan-penghasil`)
- Units (`/api/unit-pembangkit`)

### Pending Endpoints
- [ ] Notifications (`/api/notifications`)
- [ ] Reports API (`/api/reports/*`)
- [ ] Expiry alerts (`/api/expiry-alerts`)
- [ ] Offline sync endpoints
- [ ] File upload endpoints

---

## API Versioning

Current Version: `v1` (default)

URL Format: `/api/{version}/{resource}`

Example: `/api/v1/log-penyimpanan`

Note: Currently using `/api` without version prefix. Will implement versioning in future.

---

**Last Updated:** 2026-01-11
