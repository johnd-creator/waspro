# PRD - WASPRO (Sistem Manajemen Limbah Divisi Lingkungan)

## 📋 Dokumen ini

**Versi:** 1.0.0
**Tanggal:** 2026-01-11
**Status:** Draft

---

## 1. Visi & Misi

### Visi
> "Seluruh cabang organisasi dapat menerima manfaat aplikasi dalam pengelolaan limbah, dan superadmin dapat melihat semua organisasi, apakah bermasalah atau tidaknya dalam mengelola limbah"

### Misi
> "Agar perusahaan dapat mendapatkan manfaat dalam pengelolaan limbah, sehingga tidak ada lagi pelanggaran aturan dalam pengelolaan limbah dan divisi lingkungan dapat juga mendapatkan manfaat dalam penilaian **PROPER HIJAU Kementerian LHK**"

---

## 2. Target Pengguna & Stakeholder

| Stakeholder | Tanggung Jawab | Unit Scope |
|-------------|----------------|------------|
| **Super Admin** | Monitoring kepatuhan semua organisasi (40-50 unit), notifikasi masalah/expiry, melihat semua data tanpa batasan | NULL (semua unit) |
| **Manajemen Lingkungan** | Lihat aktivitas unit, ambil keputusan biaya, monitoring expiry limbah, buat keputusan untuk pengelolaan limbah | Unit sendiri |
| **Supervisor** | View log limbah, **APPROVE/REJECT** pencatatan operator, memiliki POV penuh seperti manajemen | Unit sendiri |
| **Operator Unit** | Input log limbah baru yang muncul/masuk monitoring | Unit sendiri |
| **Viewer** | Lihat data tanpa melakukan perubahan (untuk audit/monitoring) | Unit sendiri |
| **Regulator (Pemerintah)** | Penilaian lingkungan, audit pengelolaan limbah - aplikasi ini menjadi referensi utama | Read-only |

---

## 3. Role & Matrix Akses

### Tabel Hak Akses per Role

| Fitur | Super Admin | Manajemen | Supervisor | Operator | Viewer | Regulator |
|--------|-------------|------------|-------------|----------|---------|-----------|
| Lihat Semua Unit | ✅ | ❌ | ❌ | ❌ | ❌ |
| Lihat Unit Sendiri | ✅ | ✅ | ✅ | ✅ | ✅ |
| Input Log Limbah | ✅ | ✅ | ✅ | ❌ | ❌ |
| Edit Log Limbah (Sendiri) | ✅ | ✅ | ❌ | ❌ | ❌ |
| Edit Log Limbah (Semua) | ✅ | ❌ | ❌ | ❌ | ❌ |
| Approve Log | ✅ | ❌ | ❌ | ❌ | ❌ |
| Reject Log | ✅ | ❌ | ❌ | ❌ | ❌ |
| Hapus Log | ✅ | ✅ | ❌ | ❌ | ❌ |
| Lihat Notifikasi Masalah | ✅ | ✅ | ❌ | ❌ | ❌ |
| Lihat KPI Kepatuhan | ✅ | ✅ | ❌ | ❌ | ✅ |
| Manajemen Biaya | ✅ | ✅ | ❌ | ❌ | ❌ |
| Export Laporan | ✅ | ✅ | ❌ | ❌ | ✅ |
| Audit Trail | ✅ | ✅ | ✅ | ❌ | ✅ |

---

## 4. Fitur Utama

### 4.1 Fitur yang Sudah Ada (v0.1.4)

#### Core Features
- ✅ CRUD Data Limbah (Jenis, Karakteristik, Sumber)
- ✅ Log Penyimpanan Limbah dengan tracking
- ✅ Sistem Peringatan Expiry (Critical, Warning, Safe, Expired)
- ✅ Pengangkutan Limbah dengan upload dokumen
- ✅ Multi-User dengan RBAC (5 role)
- ✅ Multi-Unit Support (3 unit: Jakarta Pusat, Surabaya, Medan)
- ✅ Dashboard & Laporan (PDF/Excel)
- ✅ API untuk Mobile App (Flutter)
- ✅ UnitScope untuk memfilter data per user
- ✅ Notifikasi Database

#### API Endpoints (Existing)
- ✅ Authentication (`/api/login`, `/api/logout`)
- ✅ Dashboard summary (`/api/dashboard/summary`)
- ✅ CRUD untuk master data (jenis limbah, perusahaan, unit, dll)
- ✅ CRUD untuk log penyimpanan
- ✅ Sync endpoint untuk offline support

### 4.2 Fitur yang Akan Dikembangkan

#### Phase 1: Core Improvements
- **Super Admin tanpa unit_id**
  - Mengubah `unit_id` Super Admin menjadi NULL
  - Update UnitScope untuk bypass filter ketika `unit_id` = NULL

- **Approval Workflow Supervisor**
  - Log limbah operator langsung TERSIMPAN tapi status = "Pending Approval"
  - Supervisor dapat APPROVE atau REJECT log limbah
  - Log approval tersimpan untuk audit

- **Notifikasi Dasar ke Super Admin**
  - Notifikasi ke Super Admin ketika unit memiliki masalah
  - Notifikasi ketika limbah expired di unit tertentu
  - Notifikasi untuk monitoring kepatuhan

#### Phase 2: Biaya & Reporting
- **Biaya Pengangkutan**
  - Biaya per jenis limbah
  - Estimasi biaya dan realisasi
  - Laporan biaya per unit/bulan/tahun

- **Laporan Biaya**
  - Export laporan biaya untuk keputusan manajemen
  - Perbandingan estimasi vs realisasi

#### Phase 3: Audit & Kepatuhan
- **Audit Trail Lengkap**
  - Log semua aktivitas user (create, update, delete)
  - Track siapa mengubah apa, kapan
  - Export Audit Log untuk regulator

- **Monitoring KPI Kepatuhan**
  - Dashboard KPI untuk monitoring kepatuhan organisasi
  - Alert ke Super Admin jika unit tidak patuh

- **Format PROPER HIJAU**
  - Laporan sesuai format Kementerian LHK
  - Format akan ditentukan nanti ketika ada regulasi

---

## 5. Workflow Approval Limbah

### Alur Approval

```
┌─────────────┐
│  Operator   │
└──────┬──────┘
       │ Input Log Limbah
       │
       ▼
┌─────────────────────────────┐
│  Log Tersimpan (Pending)  │
│  Status: Pending Approval   │
└───────────┬─────────────┘
            │
            │ Supervisor Action
            ├─────────────────┬─────────────────┐
            │                 │                 │
            ▼                 ▼                 ▼
       ┌─────────┐      ┌─────────┐      ┌─────────┐
       │ Approve │      │ Reject  │      │ Ignore  │
       └────┬────┘      └────┬────┘      └─────────┘
            │                │
            ▼                ▼
    ┌──────────────┐  ┌──────────────┐
    │ Status:     │  │ Status:     │
    │ Approved    │  │ Rejected    │
    │ (Aktif)    │  │ (Ditolak)   │
    └──────────────┘  └──────────────┘
```

### Detail Workflow

1. **Operator Input Log**
   - Operator menginput data limbah baru
   - Data langsung TERSIMPAN di database
   - Status di-set ke "Pending Approval"

2. **Supervisor Review**
   - Supervisor melihat log dengan status "Pending Approval"
   - Supervisor dapat:
     - **APPROVE**: Status berubah ke "Approved" (Aktif)
     - **REJECT**: Status berubah ke "Rejected" (dengan alasan)
     - **IGNORE**: Tidak melakukan apa-apa (tetap pending)

3. **Log Approval**
   - Setiap action supervisor dicatat di `approval_log`
   - Termasuk: siapa, kapan, action, alasan (jika reject)

---

## 6. Roadmap Development

### Phase 1: Core Improvements (Prioritas Tinggi)
- [ ] Super Admin tanpa unit_id (Opsi A)
- [ ] Approval workflow Supervisor
- [ ] Notifikasi dasar ke Super Admin
- [ ] Update UnitScope untuk NULL unit_id

### Phase 2: Biaya & Reporting (Prioritas Sedang)
- [ ] Tabel biaya per jenis limbah
- [ ] Fitur estimasi & realisasi biaya
- [ ] Laporan biaya per unit/bulan/tahun
- [ ] Export laporan biaya (PDF/Excel)

### Phase 3: Audit & Kepatuhan (Prioritas Sedang)
- [ ] Tabel audit_log untuk tracking aktivitas
- [ ] Dashboard KPI kepatuhan
- [ ] Alert ke Super Admin untuk masalah
- [ ] Export Audit Log untuk regulator
- [ ] Format PROPER HIJAU (ketika ada regulasi)

### Phase 4: Flutter App Enhancement (Prioritas Rendah)
- [ ] Notifikasi push di Flutter app
- [ ] Offline sync improvement
- [ ] Approval workflow di Flutter app

---

## 7. Technical Debt & Improvements

### Current Issues

| Issue | Priority | Impact |
|-------|-----------|---------|
| UnitScope hanya di `LogPenyimpananLimbah` | High | Model lain belum terfilter |
| Tidak ada Audit Log table | High | Tidak ada tracking aktivitas |
| Notifikasi masih basic (database only) | Medium | Belum ada real-time alert |
| Supervisor approval belum diimplementasikan | High | Workflow approval belum jalan |
| Super Admin masih punya unit_id | High | Super Admin tidak benar-benar global |

### Improvements Needed

1. **UnitScope Expansion**
   - Tambah UnitScope ke model lain jika diperlukan
   - Dokumentasikan di `docs/development/backend.md`

2. **Audit Trail System**
   - Buat tabel `audit_log`
   - Buat middleware atau trait untuk auto-log
   - Export audit log

3. **Approval System**
   - Implementasi status approval di `log_penyimpanan_limbah`
   - Buat endpoint API untuk approve/reject
   - UI untuk supervisor approve/reject

4. **Biaya System**
   - Buat tabel `biaya_limbah`
   - Integrasi dengan `jenis_limbah`
   - Laporan biaya

---

## 8. Non-Functional Requirements

### Skalabilitas
- Target: **40-50 unit organisasi**
- Database: MySQL 8+ untuk production
- Queue system: Redis untuk production
- Caching: Database cache untuk dashboard

### Keamanan
- UnitScope untuk data isolation
- RBAC dengan 5 role
- Audit trail untuk semua aktivitas
- Rate limiting untuk API
- HTTPS untuk production

### Performa
- Pagination untuk list endpoints
- Caching untuk dashboard charts
- Index database untuk query cepat
- N+1 query optimization

### Ketersediaan
- Offline sync untuk Flutter app
- Client UUID tracking
- Delta sync untuk bandwidth efisiensi

---

## 9. Database Changes Needed

### Tabel Baru

#### `audit_log`
```sql
- id (PK)
- user_id (FK)
- action (create/update/delete)
- table_name (nama tabel yang diubah)
- record_id (ID record yang diubah)
- old_value (JSON)
- new_value (JSON)
- created_at
```

#### `biaya_limbah`
```sql
- id (PK)
- kode_limbah (FK ke jenis_limbah)
- biaya_per_kg (decimal)
- biaya_pengangkutan_per_kg (decimal)
- mulai_berlaku (date)
- akhir_berlaku (date, nullable)
- created_at
- updated_at
```

#### `approval_log`
```sql
- id (PK)
- log_id (FK ke log_penyimpanan_limbah)
- approved_by (FK ke pengguna_sistem)
- action (approve/reject)
- rejected_reason (text, nullable)
- created_at
```

### Kolom Baru di Tabel Existing

#### `log_penyimpanan_limbah`
```sql
- approval_status (enum: pending/approved/rejected)
- approved_by (FK ke pengguna_sistem, nullable)
- approved_at (timestamp, nullable)
- rejected_reason (text, nullable)
```

#### `pengguna_sistem`
```sql
- unit_id (nullable - untuk Super Admin)
```

---

## 10. API Changes untuk Flutter

### Endpoints Baru

#### Approval
- `POST /api/log-penyimpanan/{id}/approve` - Supervisor approve
- `POST /api/log-penyimpanan/{id}/reject` - Supervisor reject

#### Audit Trail
- `GET /api/audit-log` - List audit trail (filterable)
- `GET /api/audit-log/{id}` - Detail audit log
- `POST /api/audit-log/export` - Export audit log (CSV/PDF)

#### Biaya
- `GET /api/biaya-limbah` - List biaya per jenis limbah
- `POST /api/biaya-limbah` - Create biaya (admin only)
- `PUT /api/biaya-limbah/{id}` - Update biaya (admin only)
- `DELETE /api/biaya-limbah/{id}` - Delete biaya (admin only)

#### KPI
- `GET /api/dashboard/kpi` - KPI kepatuhan per unit
- `GET /api/dashboard/compliance` - Compliance summary untuk Super Admin

#### Notifikasi
- `GET /api/notifications/problems` - Notifikasi masalah untuk Super Admin
- `GET /api/notifications/expiry` - Notifikasi expiry untuk Super Admin

---

## 11. Regulasi & Kepatuhan (PROPER HIJAU)

### PROPER HIJAU Kementerian LHK
- Aplikasi ini menjadi referensi utama untuk penilaian PROPER HIJAU
- Format laporan akan ditentukan nanti ketika ada regulasi dari Kementerian LHK

### Kebutuhan Regulator
- Audit Trail lengkap untuk tracking aktivitas
- Export Audit Log untuk keperluan audit
- Laporan sesuai format yang disyaratkan

---

## 12. Timeline & Prioritas

### No Target Timeline
- Tidak ada deadline produksi (fleksibel)
- Development dapat disesuaikan dengan prioritas bisnis

### Prioritas Implementasi
1. **Super Admin tanpa unit_id** - Foundation untuk multi-tenant
2. **Approval Workflow Supervisor** - Core business logic
3. **Notifikasi ke Super Admin** - Monitoring masalah
4. **Biaya & Reporting** - Decision support untuk manajemen
5. **Audit Trail** - Compliance & regulator
6. **PROPER HIJAU Format** - Ketika ada regulasi

---

## 13. Skala & Kapasitas

### Estimasi Data
- **Unit Organisasi:** 40-50 unit
- **User per Unit:** 3-5 user (Operator, Supervisor, Manajemen)
- **Total User:** 120-250 user
- **Log Limbah per Hari per Unit:** 5-10 log
- **Total Log per Hari:** 200-500 log
- **Total Log per Tahun:** 73,000-182,500 log

### Kapasitas Database
- **MySQL** recommended untuk production
- **Indexing** penting untuk performa
- **Partitioning** mungkin diperlukan untuk tahun-tahun tertentu

---

## 14. Catatan Penting

1. **PROPER HIJAU Format**: Saat ini belum ada format yang spesifik. Akan ditambahkan ketika ada regulasi dari Kementerian LHK.

2. **Biaya Pengangkutan**: Fokus pada biaya per jenis limbah, bukan biaya penyimpanan (limbah disimpan di organisasi).

3. **Supervisor POV**: Supervisor memiliki POV penuh seperti manajemen, dapat view log dan approve.

4. **Super Admin**: Tidak punya unit_id (NULL), dapat melihat semua organisasi.

5. **No Role Auditor**: Supervisor melakukan fungsi approval, tidak perlu role auditor terpisah.

---

**Last Updated:** 2026-01-11
**Version:** 1.0.0
**Status:** Draft
