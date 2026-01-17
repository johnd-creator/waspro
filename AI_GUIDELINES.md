# 🤖 AI Agent Guidelines for WASPRO Development

## 📋 Purpose

This document serves as master rulebook for all AI agents working on WASPRO (Waste Management System for Environment Division) project. All changes made by AI agents MUST be documented in appropriate files under `docs/development/`.

## 📋 Dokumentasi Penting

Sebelum memulai pengembangan fitur baru:
1. Baca dan pahami `prd_app.md` untuk memahami arah aplikasi
2. Referensi PRD untuk memastikan fitur sesuai visi/misi
3. Konsultasikan dengan user jika ada ketidaksesuaian

## 🎯 Project Context

**WASPRO** is a Laravel 12.x-based waste management system with:
- Backend: Laravel 12.x, PHP 8.2+
- Frontend: Tailwind CSS 4.x, Vite, Blade
- Database: SQLite (development), MySQL 8+ (production)
- Mobile API: RESTful API for Flutter app
- Multi-tenancy: Organization/Unit-based access control
- Division: Environment Division (Divisi Lingkungan)

---

## ⚠️ CRITICAL RULES (Do NOT Violate)

### 1. **READ BEFORE WRITE**
- ALWAYS read the entire file before editing
- Understand existing code conventions and patterns
- Never assume structure without verification

### 2. **NO MALICIOUS CODE**
- Refuse to write code that could harm the system or users
- Refuse code that exploits vulnerabilities, even for "educational purposes"
- Follow security best practices at all times

### 3. **LANGUAGE & COMMUNICATION**
- Use Indonesian for code comments and user-facing text
- Use English for technical documentation (this file, API docs)
- Be concise in responses (≤ 4 lines unless asked for detail)
- No unnecessary preamble or postamble

### 4. **NO UNNECESSARY COMMENTS**
- Do NOT add comments unless explicitly requested
- Code should be self-documenting
- Remove old comments when refactoring

### 5. **COMMIT POLICY**
- NEVER commit unless explicitly asked by the user
- It's VERY IMPORTANT to only commit when explicitly asked
- User will feel proactive if you commit without permission

### 6. **RUN LINTING & TYPECHECKING**
- After ANY code changes, run linting commands:
  - `composer run lint` (if exists)
  - `npm run lint` (if exists)
  - `php artisan pint` (Laravel Pint)
  - `npm run typecheck` (if exists)
- If commands fail, ask the user for the correct command

### 7. **USE EXISTING PATTERNS**
- Check what libraries/frameworks are already in use
- Follow existing code style in similar files
- Don't add new dependencies unless necessary

### 8. **DOCUMENT EVERY CHANGE**
- Record all changes in appropriate `docs/development/*.md` files
- Format: Clear bullet points with file paths and line numbers
- Include reasoning for changes

---

## 📁 Documentation Structure

All AI-generated changes MUST be documented here:

### `docs/development/ux.md`
- UI/UX changes
- New views, components, or pages
- Navigation changes
- User experience improvements

### `docs/development/security.md`
- Security fixes or improvements
- Authentication/authorization changes
- Input validation updates
- Data protection measures

### `docs/development/testing.md`
- New tests added
- Test fixes or improvements
- Testing strategies
- Test coverage updates

### `docs/development/backend.md`
- Backend logic changes
- Database migrations
- Model relationships
- Business logic updates
- API endpoint changes (backend side)

### `docs/api/mobile-guide.md`
- API changes for mobile developers
- Endpoint documentation
- Request/response formats
- Authentication for mobile
- Breaking changes

---

## 🏗️ Architecture Rules

### Multi-Unit/Organization Scope
- **UnitScope** is already implemented in `app/Models/Scopes/UnitScope.php`
- Currently applies to: `LogPenyimpananLimbah`
- Super Admin sees all units; other users see only their unit's data
- When adding scope to new models, document in `docs/development/backend.md`
- **Super Admin unit_id**: Should be NULL (no unit assignment) - see `prd_app.md`

### Model Conventions
- Use Indonesian for database table names (e.g., `pengguna_sistem`, `log_penyimpanan_limbah`)
- Use English for Model class names (e.g., `PenggunaSistem`, `LogPenyimpananLimbah`)
- Primary keys should follow pattern: `*_id` (e.g., `user_id`, `log_id`)

### API Development
- Use OpenAPI specification in `docs/openapi/k3-api.yaml`
- Update Postman collection in `docs/postman/k3-api.postman_collection.json`
- Document mobile-facing changes in `docs/api/mobile-guide.md`

---

## 🔒 Security Considerations

1. **Never commit secrets** - `.env`, credentials, API keys
2. **Use prepared statements** - Eloquent ORM handles this
3. **Validate all inputs** - Use Laravel validation
4. **Sanitize user data** - Never trust user input
5. **Use HTTPS** - In production
6. **Implement rate limiting** - Already configured in `.env`

---

## 🧪 Testing Rules

1. **Write tests for new features** - Use PHPUnit
2. **Test critical paths** - Authentication, authorization, data operations
3. **Document test coverage** - In `docs/development/testing.md`

---

## 📦 Common Commands

### Development
```bash
# Start all services (server, queue, logs, vite)
composer run dev

# Start only Laravel server
php artisan serve

# Run migrations with seeds
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Code Quality
```bash
# Laravel Pint (code formatter)
php artisan pint

# Run tests
php artisan test
```

### Database
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Fresh migration with seeds
php artisan migrate:fresh --seed
```

---

## 📝 Change Documentation Format

When documenting changes, use this format:

```markdown
### [YYYY-MM-DD] [Feature/Fix/Refactor] Title

**Files Changed:**
- `path/to/file.php` - Description of change (line X)
- `path/to/another/file.php` - Description of change (line Y)

**Reasoning:**
Why the change was made.

**Impact:**
What other parts of the system are affected.
```

---

## 🚫 Prohibited Actions

1. DO NOT add new emojis to files unless explicitly requested
2. DO NOT modify `.env` without permission
3. DO NOT commit to git unless explicitly asked
4. DO NOT skip pre-commit hooks
5. DO NOT add comments unless asked
6. DO NOT write long explanations unless the user asks for detail

---

## ✅ Quality Checklist

Before considering a task complete:

- [ ] Code follows existing patterns
- [ ] No unnecessary comments added
- [ ] Language is consistent (Indonesian for comments)
- [ ] Security best practices followed
- [ ] Changes documented in appropriate `docs/development/*.md`
- [ ] Linting/typechecking passed (if commands available)
- [ ] Tests added (if applicable)
- [ ] No secrets or credentials committed

---

## 📞 Questions or Clarifications

If unsure about any rule:
1. Check the existing codebase for similar implementations
2. Read the relevant documentation files
3. Ask the user for clarification

---

**Last Updated:** 2026-01-11
**Version:** 1.1.0
