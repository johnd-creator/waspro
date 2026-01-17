# Quick Development Data - WASPRO

## Quick Start

Generate development data in seconds:

```bash
php artisan db:seed --class=QuickDevSeeder
```

**Execution Time**: ~30 seconds

---

## What Gets Generated

- **20 Users** (5 admins, 5 supervisors, 8 operators, 2 viewers)
- **100 Logs** (50 tersimpan, 30 diangkut, 20 expired)
- **10 Jenis Limbah** (8 active, 2 inactive)

---

## Login Credentials

**Email**: `admin.quick0@waspro.com`  
**Password**: `password`

All users follow pattern: `{role}.quick{number}@waspro.com`

---

## Verification

```php
// In Tinker
echo "Users: " . PenggunaSistem::where('email_address', 'LIKE', '%.quick%@waspro.com')->count() . "\n";
echo "Logs: " . LogPenyimpananLimbah::withoutGlobalScopes()->count() . "\n";
echo "Jenis: " . JenisLimbah::count() . "\n";
```

---

## Cleanup

```php
// In Tinker
PenggunaSistem::where('email_address', 'LIKE', '%.quick%@waspro.com')->delete();
```

---

## Other Seeders

- **QuickDevSeeder** - 20 users, 100 logs (this one)
- **TestingScenariosSeeder** - 40 users, 575 logs (7 scenarios)
- **PerformanceTestingSeeder** - 200 users, 10,000 logs (production scale)
