# 🏗️ WASPRO Development Structure

## 📁 Project Structure Overview

```
waspro/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Request handlers
│   │   ├── Middleware/         # Request/response middleware
│   │   ├── Requests/           # Form request validation
│   │   └── Livewire/           # Livewire components (if any)
│   ├── Models/                 # Eloquent models
│   │   └── Scopes/             # Query scopes (UnitScope, etc.)
│   ├── Services/               # Business logic services
│   ├── Exceptions/             # Custom exceptions
│   └── Providers/              # Service providers
├── bootstrap/                  # Framework bootstrapping
├── config/                     # Configuration files
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── docs/                       # Documentation (see below)
├── public/                     # Public web root
│   └── storage -> ../storage/app/public
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── layouts/            # Layout templates
│   │   ├── components/         # Reusable components
│   │   └── [feature]/          # Feature-specific views
│   ├── css/                    # CSS files (Tailwind)
│   └── js/                     # JavaScript files
├── routes/
│   ├── api.php                 # API routes
│   ├── web.php                 # Web routes
│   └── channels.php            # Broadcasting channels
├── storage/                    # Storage directories
│   ├── app/
│   │   └── public/             # Public files
│   ├── framework/              # Framework cache/logs
│   └── logs/                   # Application logs
├── tests/                      # Test files
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
├── .env                        # Environment configuration (DO NOT COMMIT)
├── .env.example                # Environment template
├── artisan                     # CLI tool
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies
├── vite.config.js              # Vite configuration
└── tailwind.config.js          # Tailwind configuration
```

---

## 📁 Documentation Structure

```
docs/
├── api/                        # API Documentation
│   ├── mobile-guide.md         # Flutter developer guide
│   ├── openapi/                # OpenAPI specifications
│   │   └── k3-api.yaml
│   └── postman/                # Postman collections
│       └── k3-api.postman_collection.json
└── development/                # Development Notes
    ├── ux.md                   # UI/UX changes
    ├── security.md             # Security considerations
    ├── testing.md              # Testing guidelines
    ├── backend.md              # Backend conventions
    └── api-changes.md          # API changes (internal)
```

---

## 📋 Naming Conventions

### Database
- **Tables:** Snake case, Indonesian plural
  - `pengguna_sistem`, `log_penyimpanan_limbah`, `unit_pembangkit`
- **Columns:** Snake case, Indonesian
  - `nama_lengkap`, `tanggal_limbah_masuk`, `kata_sandi_hash`
- **Primary Keys:** `{singular}_id`
  - `user_id`, `log_id`, `perusahaan_id`
- **Foreign Keys:** `{reference}_id`
  - `unit_id`, `perusahaan_id`, `jenis_limbah_id`

### Models
- **Class Names:** PascalCase, singular, English
  - `PenggunaSistem`, `LogPenyimpananLimbah`, `UnitPembangkit`
- **Relationships:** English, descriptive
  - `unit()`, `logPenyimpanan()`, `perusahaanPenghasil()`
- **Scopes:** camelCase, prefixed with `scope`
  - `scopeByUnit()`, `scopeActive()`, `scopeExpired()`

### Controllers
- **Class Names:** `{Resource}Controller`, PascalCase
  - `PenggunaSistemController`, `LogPenyimpananLimbahController`
- **Methods:** camelCase, RESTful
  - `index()`, `store()`, `update()`, `destroy()`

### Views
- **Directories:** kebab-case, singular or plural as appropriate
  - `log-penyimpanan/`, `reports/`, `auth/`
- **Files:** kebab-case
  - `index.blade.php`, `create.blade.php`, `edit.blade.php`

### Routes
- **Route Names:** dot notation, lowercase
  - `pengguna-sistem.index`, `log-penyimpanan.store`
- **URLs:** kebab-case, plural for collections
  - `/pengguna-sistem`, `/log-penyimpanan/{id}`

---

## 🎨 Frontend Conventions

### Blade Components
- Use `x-` prefix for custom directives
- Use `@slot` for component parameters
- Follow BEM-like naming for CSS classes

### Tailwind CSS
- Use utility classes consistently
- Create custom config for project-specific colors
- Use `@apply` sparingly; prefer inline utilities

### JavaScript
- Use ES6+ syntax
- Use Alpine.js for interactivity (if needed)
- Follow Vue/React patterns if using those frameworks

---

## 🔒 Security Conventions

### Input Validation
- Use Form Request classes for validation
- Validate all user inputs
- Sanitize file uploads

### Authentication & Authorization
- Use Laravel's built-in authentication
- Implement role-based access control (RBAC)
- Use middleware for route protection

### Data Protection
- Hash passwords using Laravel's built-in methods
- Use HTTPS in production
- Implement CSRF protection

---

## 🧪 Testing Conventions

### Test Organization
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Use descriptive test names
- Follow Arrange-Act-Assert pattern

### Test Data
- Use factories for test data
- Seed database with consistent test data
- Clean up after tests

---

## 📦 Configuration

### Environment Variables
- Store in `.env` (never commit)
- Document in `.env.example`
- Use descriptive variable names

### Config Files
- Use Laravel's config system
- Cache config in production
- Never commit sensitive data

---

## 🔄 Database Conventions

### Migrations
- Use descriptive migration names
- Include indexes for foreign keys
- Add `->onDelete('cascade')` or `restrict` as appropriate
- Use `->default()` for non-nullable columns

### Seeders
- Use factories for data generation
- Seed reference data (e.g., roles, waste types)
- Seed test users for development

---

## 📝 Code Quality

### PHP Standards
- Follow PSR-12 coding standard
- Use Laravel Pint for formatting
- Run `php artisan pint` before committing

### JavaScript Standards
- Use ESLint configuration
- Run `npm run lint` before committing

### Documentation
- Use PHPDoc for class/method comments
- Keep comments minimal; code should be self-documenting
- Document complex business logic

---

## 🚀 Deployment Considerations

### Production Build
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

### Environment Setup
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure mail settings
- Set up queue workers

---

## 🔍 Code Review Checklist

- [ ] Follows naming conventions
- [ ] No unnecessary comments
- [ ] Language consistent (Indonesian for comments)
- [ ] Security best practices applied
- [ ] Tests included (if applicable)
- [ ] Documentation updated
- [ ] Linting passed
- [ ] No secrets committed

---

**Last Updated:** 2026-01-11
