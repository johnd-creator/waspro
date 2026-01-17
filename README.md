# 🏭 WASPRO - Sistem Manajemen Limbah 

<p align="center">
  <img src="public/images/logo.png" width="200" alt="WASPRO Logo">
</p>

<p align="center">
  <strong>Waste Management System for Environment Division</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Tailwind-4.x-cyan.svg" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Version-0.1.4-brightgreen.svg" alt="App Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Tentang WASPRO

WASPRO (Waste Management System for Environment Division) adalah sistem informasi berbasis web untuk mengelola siklus hidup limbah industri (pencatatan, penyimpanan, pengangkutan, dan pelaporan) guna mendukung kepatuhan pengelolaan limbah dan kebutuhan monitoring Divisi Lingkungan.

> **Catatan ruang lingkup:** WASPRO **bukan** aplikasi K3/HSE/safety (bukan untuk manajemen insiden, hazard, permit to work, audit K3, dll).

- 📊 **Monitoring Real-time** – Memantau status limbah, stok, dan pipeline pengangkutan
- 🗂️ **Manajemen Data Limbah** – Pencatatan jenis, karakteristik, dan sumber limbah
- ⚠️ **Sistem Peringatan** – Notifikasi otomatis untuk limbah yang mendekati tanggal kedaluwarsa
- 📈 **Laporan Komprehensif** – Laporan PDF/Excel untuk perusahaan, unit, status, dan tipe limbah
- 🚛 **Tracking Pengangkutan** – Monitoring proses pengangkutan termasuk histori dan bukti dokumen
- 👥 **Multi-User Management** – Role-based access control lengkap dengan audit aktivitas
- 🏢 **Multi-Unit Support** – Manajemen data multi perusahaan / unit pembangkit

## ✨ Apa yang baru di v0.1.4

- 🌐 **API Hardening** – CORS configurable, throttling bawaan (`throttle:api`), dan middleware logging request untuk audit
- 📄 **Dokumen Limbah** – Upload manifest/bukti angkut per log, tersimpan di storage publik dan dapat diunduh ulang
- 🔔 **Notifikasi Otomatis** – Event & listener baru mengirim notifikasi database ke Super Admin/Admin saat dokumen diunggah
- 📜 **API Docs** – Integrasi Scribe/OpenAPI lengkap dengan Postman collection (`docs/postman/k3-api.postman_collection.json`, *legacy filename*)
- 🧱 **Dev Experience** – Seeder & factory diperbarui, workflow GitHub Actions menjalankan API test suite otomatis

## 🚀 Teknologi Utama

- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Tailwind CSS 4.x, Vite, Blade components
- **Database**: MySQL 8+ (SQLite alternatif untuk pengembangan)
- **Queue & Schedule**: Database queue driver + Artisan scheduler
- **Export & PDF**: Laravel Excel & DomPDF
- **Authentication**: Session-based Laravel auth dengan role management

## 📦 Instalasi Lokal

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi dengan ekstensi `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `bcmath`, `intl`, `gd`
- Composer 2.x
- Node.js 18 LTS atau lebih baru & npm
- MySQL / MariaDB (atau SQLite)
- Redis opsional untuk cache/queue (default menggunakan database)

### Langkah Setup

1. **Clone Repository & Checkout Rilis**
   ```bash
   git clone https://github.com/johnd-creator/waspro.git
   cd waspro
   git checkout v0.1.4
   ```

2. **Install Dependencies**
   ```bash
   composer install --no-interaction --prefer-dist
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   - Edit `.env` untuk menyesuaikan koneksi database, mail, dan konfigurasi spesifik
   - Opsi SQLite dev: set `DB_CONNECTION=sqlite` dan isi `DB_DATABASE=/absolute/path/database.sqlite`

4. **Migrasi & Seeder**
   ```bash
   php artisan migrate --seed
   ```

5. **Utility Tambahan**
   ```bash
   php artisan storage:link
   ```
   - Jalankan queue worker saat fitur notifikasi/refresh diperlukan: `php artisan queue:work`

6. **Menjalankan Aplikasi**
   ```bash
   # Development terpisah
   npm run dev
   php artisan serve

   # Atau jalankan semuanya sekaligus
   composer run dev
   ```

7. **Build Produksi (opsional)**
   ```bash
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 🔧 Konfigurasi Penting

### Database (MySQL contoh)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=waspro_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Email / Notifikasi
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@waspro.example"
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue & Cache
```env
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

### CORS & Rate Limiting
```env
CORS_ALLOWED_ORIGINS=http://localhost:8000,http://localhost:3000
CORS_SUPPORTS_CREDENTIALS=true
API_RATE_LIMIT=120
API_RATE_LIMIT_DECAY_SECONDS=60
```

## 👥 Akun Default

Aplikasi ini menyediakan beberapa akun demo untuk development dan testing:

### Development Environment

| Role | Email | Password | Unit |
|------|-------|----------|-------|
| Super Admin | superadmin@waspro.com | password123 | Global (NULL) |
| Administrator | admin.jakarta@waspro.com | password123 | PT Hidayanto Unit |
| Supervisor | manager.surabaya@waspro.com | password123 | PT Hidayanto Unit |
| Operator | operator1.jakarta@waspro.com | password123 | PT Hidayanto Unit |
| Viewer | viewer.jakarta@waspro.com | password123 | PT Hidayanto Unit |

### Catatan Penting:

1. **Super Admin**
   - Memiliki akses GLOBAL ke semua unit pembangkit
   - Tidak terikat ke unit manapun (`unit_id = NULL`)
   - Hanya boleh ada SATU Super Admin di sistem
   - Tidak bisa dihapus atau dinonaktifkan
   - Dapat dikelola hanya oleh Super Admin sendiri

2. **Administrator & Role Lainnya**
   - Terikat ke unit spesifik (`unit_id` wajib diisi)
   - Hanya bisa melihat dan mengelola data unit sendiri
   - Administrator tidak bisa membuat Super Admin baru

3. **Konfigurasi Super Admin**
   - Super Admin dapat dikonfigurasi melalui environment variables:
     - `SUPERADMIN_EMAIL`: Email default Super Admin
     - `SUPERADMIN_PASSWORD`: Password default
     - `SUPERADMIN_NAME`: Nama lengkap default
   - Jika tidak di-set, akan menggunakan nilai default dari seeder

Gunakan akun ini hanya di lingkungan pengembangan.

## 📚 Dokumentasi Tambahan

- `prd_app.md` – PRD (visi, role, dan scope aplikasi)
- `DEVELOPMENT_STRUCTURE.md` – Struktur project & konvensi pengembangan
- `docs/api/mobile-guide.md` – Panduan API untuk mobile (Flutter)
- `docs/openapi/k3-api.yaml` – OpenAPI spec (*legacy filename*)
- `docs/development/backend.md` – Catatan perubahan backend
- `docs/development/security.md` – Catatan perubahan security
- `docs/development/testing.md` – Catatan testing
- `CHANGELOG.md` – Riwayat perilisan

## 🔁 Alur Git & Rilis

Untuk merilis versi resmi ke GitHub dan memastikan hanya branch `main` yang aktif:

1. **Pastikan repositori bersih**
   ```bash
   git checkout main
   git pull origin main
   git status
   ```
2. **Commit perubahan terbaru**
   ```bash
   git add .
   git commit -m "chore: prepare release v0.1.4"
   ```
3. **Sinkronisasi dengan GitHub**
   ```bash
   git push origin main
   ```
4. **Tag rilis**
   ```bash
   git tag -a v0.1.4 -m "Release v0.1.4"
   git push origin v0.1.4
   ```
5. **Hapus branch lain di remote (opsional)**
   ```bash
   git push origin --delete nama-branch
   ```

> **Catatan:** Langkah di atas tidak dapat dijalankan otomatis dari README ini. Jalankan perintah secara manual di mesin pengembangan Anda.

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur (`git switch -c feature/nama-fitur`)
3. Commit perubahan (`git commit -m "feat: tambah nama fitur"`)
4. Push branch (`git push origin feature/nama-fitur`)
5. Ajukan Pull Request ke `main`

Silakan sertakan screenshot / GIF untuk perubahan UI dan jelaskan langkah testing yang dilakukan.

## 📄 Lisensi

Proyek ini berlisensi MIT – lihat [LICENSE](LICENSE).

## 📞 Support & Feedback

- 📧 Email: ubohbsr.sis@plnindonesiapower.co.id
- 🐛 Laporkan bug: [GitHub Issues](https://github.com/johnd-creator/waspro/issues)
- 💬 Diskusi internal: gunakan kanal komunikasi tim

---

<p align="center">
  Made with ❤️ for better waste management and environmental compliance
</p>
