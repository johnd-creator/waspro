# 🏭 WASPRO - Sistem Manajemen Limbah 

<p align="center">
  <img src="public/images/logo.png" width="200" alt="WASPRO Logo">
</p>

<p align="center">
  <strong>Waste Management System for Occupational Health and Safety</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Tailwind-4.x-cyan.svg" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Version-0.1.4-brightgreen.svg" alt="App Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Tentang WASPRO

WASPRO (Waste Management System for Occupational Health and Safety) adalah sistem informasi berbasis web yang dirancang untuk mengelola siklus hidup limbah industri sesuai standar Keselamatan dan Kesehatan Kerja (K3). Sistem ini membantu perusahaan dalam:

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
- 📜 **API Docs** – Integrasi Scribe/OpenAPI lengkap dengan Postman collection (`docs/postman/k3-api.postman_collection.json`)
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
MAIL_FROM_ADDRESS="noreply@k3limbah.com"
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

Seeder bawaan menambahkan akun berikut untuk pengujian:

| Role | Username | Password |
|------|----------|----------|
| Super Admin | superadmin | password |
| Admin Unit  | admin       | password |
| Operator    | operator    | password |

Gunakan akun ini hanya di lingkungan pengembangan.

## 📚 Dokumentasi Tambahan

- `docs/user-manual.md` – Panduan pengguna (UI & fitur)
- `docs/api.md` – Referensi endpoint API
- `docs/deployment.md` – Panduan deployment produksi
- `CHANGELOG.md` – Riwayat perilisan (termasuk v0.1.4)

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
  Made with ❤️ for better waste management and occupational safety
</p>

graph TD
    %% Definisi Swimlane berdasarkan Role dan Penanggung Jawab (PJ)
    subgraph Pemicu ["⚡ Pemicu Insiden"]
        A1[Sistem NMS mendeteksi Anomali]
        A2[Pengguna melaporkan Gangguan]
    end

    subgraph NOC ["👤 Role: Staff IT (NOC/Operator) | PJ: Assistant Manager IT"]
        B1(Monitoring & Deteksi Alert Real-time)
        B2[Mengirim Alert/Notifikasi]
    end

    subgraph Helpdesk ["👤 Role: Staff IT (Helpdesk) | PJ: Assistant Manager IT"]
        C1(Menerima Alert/Laporan & Registrasi Tiket)
        C2{Klasifikasi & Prioritas?}
        C3a[Masalah Sederhana / Panduan User]
        C3b[Teruskan ke Technical Support]
        C4(Verifikasi dengan Pengguna/NMS)
        C5{Verifikasi Berhasil?}
        C6[Dokumentasi ke Knowledge Base & Tutup Tiket]
    end

    subgraph TechSupport ["👤 Role: Staff IT (Technical Support) | PJ: Assistant Manager IT"]
        D1(Investigasi & Diagnosis Root Cause)
        D2[Lakukan Penanganan Awal misal: Restart]
        D3{Masalah Terselesaikan?}
        D4[Eskalasi ke Tingkat Lanjut]
        D5(Resolusi & Pemulihan Sistem)
    end

    subgraph Specialist ["👤 Role: Officer IT (Senior Specialist)/Vendor | PJ: Manager IT"]
        E1(Investigasi Mendalam & Penanganan Teknis Rumit)
        E2{Masalah Terselesaikan?}
    end

    subgraph Management ["👥 Role: Assistant Manager & Manager IT"]
        F1(Review Insiden Bulanan & Pembuatan Laporan oleh Ast. Mgr)
        F2[Review Akhir oleh Manager IT]
    end

    %% Alur Proses
    A1 -->|Alert Otomatis| B1
    B1 --> B2
    B2 -->|Alert| C1
    A2 -->|Laporan Langsung| C1

    C1 --> C2
    C2 -->|Minor/Pertanyaan| C3a
    C2 -->|Major/Kritis/Teknis| C3b
    
    C3a --> C4
    C3b --> D1

    D1 --> D2
    D2 --> D3
    D3 -->|Ya| D5
    D3 -->|Tidak, Rumit| D4

    D4 --> E1
    E1 --> E2
    E2 -->|Ya| D5
    E2 -->|Tidak| E1

    D5 -->|Sistem Pulih| C4
    C4 --> C5
    C5 -->|Ya, Normal| C6
    C5 -->|Tidak, Masih Bermasalah| D1

    C6 -->|Tiket Ditutup| F1
    F1 --> F2

    %% Styling untuk kejelasan
    classDef process fill:#f9f,stroke:#333,stroke-width:2px;
    classDef decision fill:#ff9,stroke:#333,stroke-width:2px;
    classDef terminator fill:#9f9,stroke:#333,stroke-width:2px;
    
    class C2,D3,C5,E2 decision;
    class A1,A2,C6,F2 process;
