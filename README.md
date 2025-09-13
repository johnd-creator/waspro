# 🏭 WASPRO - Sistem Manajemen Limbah K3

<p align="center">
  <img src="public/images/logo.png" width="200" alt="WASPRO Logo">
</p>

<p align="center">
  <strong>Waste Management System for Occupational Health and Safety</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Tailwind-3.x-cyan.svg" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Tentang WASPRO

WASPRO (Waste Management System for Occupational Health and Safety) adalah sistem informasi berbasis web yang dirancang khusus untuk mengelola limbah industri dengan standar Keselamatan dan Kesehatan Kerja (K3). Sistem ini membantu perusahaan dalam:

- 📊 **Monitoring Real-time** - Pemantauan status limbah secara real-time
- 🗂️ **Manajemen Data Limbah** - Pencatatan dan kategorisasi jenis limbah
- ⚠️ **Sistem Peringatan** - Notifikasi otomatis untuk limbah mendekati expired
- 📈 **Laporan Komprehensif** - Generate laporan bulanan, tahunan, dan custom
- 🚛 **Tracking Pengangkutan** - Monitoring proses pengangkutan limbah
- 👥 **Multi-User Management** - Sistem role-based access control
- 🏢 **Multi-Unit Support** - Mendukung multiple unit pembangkit

## ✨ Fitur Utama

### 🎯 Dashboard Interaktif
- Statistik real-time limbah per kategori
- Grafik trend penyimpanan limbah
- Alert limbah mendekati expired
- Quick actions untuk operasi harian

### 📝 Manajemen Limbah
- **Jenis Limbah**: Kategorisasi berdasarkan karakteristik dan bahaya
- **Log Penyimpanan**: Pencatatan detail setiap aktivitas penyimpanan
- **Tracking Expired**: Monitoring otomatis tanggal kadaluarsa
- **Pengangkutan**: Manajemen proses pengangkutan dan disposal

### 📊 Sistem Pelaporan
- Laporan bulanan per unit/perusahaan
- Export ke Excel/PDF
- Laporan compliance K3
- Analytics dan insights

### 🔐 Keamanan & Akses
- Role-based permissions (Super Admin, Admin Unit, Operator)
- Audit trail untuk semua aktivitas
- Session management yang aman
- Data encryption

## 🚀 Teknologi

- **Backend**: Laravel 11.x dengan PHP 8.2+
- **Frontend**: Tailwind CSS 3.x + Alpine.js
- **Database**: MySQL 8.0+
- **Build Tool**: Vite
- **Authentication**: Laravel Sanctum
- **Export**: Laravel Excel, DomPDF

## 📦 Instalasi

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0+
- Web server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/johnd-creator/waspro.git
   cd waspro
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   ```bash
   # Edit .env file dengan konfigurasi database
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

## 🔧 Konfigurasi

### Database
Edit file `.env` untuk konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=waspro_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Email (Opsional)
Untuk fitur notifikasi email:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

## 👥 Default Users

Setelah seeding, gunakan akun berikut:

| Role | Username | Password |
|------|----------|----------|
| Super Admin | superadmin | password |
| Admin Unit | admin | password |
| Operator | operator | password |

## 📚 Dokumentasi

- [User Manual](docs/user-manual.md)
- [API Documentation](docs/api.md)
- [Deployment Guide](docs/deployment.md)
- [Changelog](CHANGELOG.md)

## 🤝 Kontribusi

Kami menerima kontribusi dari komunitas! Silakan:

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Support

Jika Anda membutuhkan bantuan:
- 📧 Email: support@waspro.com
- 📱 WhatsApp: +62-xxx-xxxx-xxxx
- 🐛 Issues: [GitHub Issues](https://github.com/johnd-creator/waspro/issues)

---

<p align="center">
  Made with ❤️ for better waste management and occupational safety
</p>
