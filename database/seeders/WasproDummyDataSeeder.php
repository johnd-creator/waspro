<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * WASPRO Comprehensive Dummy Data Seeder
 *
 * Specifications:
 * - 5 Unit Pembangkit (Jakarta, Surabaya, Medan, Bandung, Semarang)
 * - 20 Jenis Limbah with varying transportation costs
 * - 5 Karakteristik Limbah
 * - 5 Kategori Kegiatan
 * - 10 Perusahaan Penghasil
 * - 50 Users (1 Super Admin, 5 Administrator, 10 Supervisor, 30 Operator, 4 Viewer)
 * - 200 Log Penyimpanan Limbah (80 Tersimpan, 70 Diangkut, 50 Expired)
 * - Approval status: 100 Pending, 60 Approved, 40 Rejected
 * - 150 Audit Logs (30 create, 90 update, 30 delete)
 */
class WasproDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("\n🚀 WASPRO Comprehensive Dummy Data Generator");
        $this->command->info("═══════════════════════════════════════════════════\n");

        // 1. Ensure Roles exist
        $this->ensureRoles();

        // 2. Create Master Data
        $units = $this->createUnitPembangkit();
        $karakteristik = $this->createKarakteristikLimbah();
        $kategori = $this->createKategoriKegiatan();
        $jenisLimbah = $this->createJenisLimbah($karakteristik);
        $perusahaan = $this->createPerusahaanPenghasil();

        // 3. Create Users
        $users = $this->createUsers($units);

        // 4. Create Log Penyimpanan Limbah
        $this->createLogPenyimpanan($units, $users, $jenisLimbah, $perusahaan, $kategori);

        // 5. Create Audit Logs
        $this->createAuditLogs($users);

        // Summary
        $this->printSummary($units, $jenisLimbah, $users);
    }

    private function ensureRoles(): void
    {
        $this->command->info('🔐 Ensuring roles exist...');

        $roles = [
            ['nama_peran' => 'Super Admin', 'deskripsi' => 'Super Administrator with full access', 'is_active' => true],
            ['nama_peran' => 'Administrator', 'deskripsi' => 'Unit Administrator', 'is_active' => true],
            ['nama_peran' => 'Supervisor', 'deskripsi' => 'Unit Supervisor for approvals', 'is_active' => true],
            ['nama_peran' => 'Operator', 'deskripsi' => 'Data entry operator', 'is_active' => true],
            ['nama_peran' => 'Viewer', 'deskripsi' => 'Read-only access', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            PeranPengguna::firstOrCreate(['nama_peran' => $role['nama_peran']], $role);
        }

        $this->command->info("   ✓ Roles verified\n");
    }

    private function createUnitPembangkit(): array
    {
        $this->command->info('🏭 Creating 5 Unit Pembangkit...');

        $unitsData = [
            ['nama_unit' => 'PLTU Jakarta', 'kota' => 'Jakarta', 'alamat_unit' => 'Jl. Listrik No. 1, Jakarta Utara', 'kode_pos' => '14120', 'telepon_unit' => '021-4401234'],
            ['nama_unit' => 'PLTU Surabaya', 'kota' => 'Surabaya', 'alamat_unit' => 'Jl. Energi No. 2, Surabaya Timur', 'kode_pos' => '60119', 'telepon_unit' => '031-5501234'],
            ['nama_unit' => 'PLTU Medan', 'kota' => 'Medan', 'alamat_unit' => 'Jl. Pembangkit No. 3, Medan Belawan', 'kode_pos' => '20411', 'telepon_unit' => '061-6601234'],
            ['nama_unit' => 'PLTU Bandung', 'kota' => 'Bandung', 'alamat_unit' => 'Jl. PLN No. 4, Bandung Selatan', 'kode_pos' => '40256', 'telepon_unit' => '022-7701234'],
            ['nama_unit' => 'PLTU Semarang', 'kota' => 'Semarang', 'alamat_unit' => 'Jl. Generator No. 5, Semarang Timur', 'kode_pos' => '50126', 'telepon_unit' => '024-8801234'],
        ];

        $units = [];
        foreach ($unitsData as $data) {
            $units[] = UnitPembangkit::firstOrCreate(
                ['nama_unit' => $data['nama_unit']],
                array_merge($data, ['status_aktif' => true, 'keterangan' => 'Unit pembangkit listrik '.$data['kota']])
            );
        }

        $this->command->info('   ✓ Created '.count($units)." units\n");

        return $units;
    }

    private function createKarakteristikLimbah(): array
    {
        $this->command->info('⚗️ Creating 5 Karakteristik Limbah...');

        $karakteristikData = [
            ['nama_karakteristik' => 'Mudah Meledak', 'status_aktif' => true],
            ['nama_karakteristik' => 'Mudah Menyala', 'status_aktif' => true],
            ['nama_karakteristik' => 'Reaktif', 'status_aktif' => true],
            ['nama_karakteristik' => 'Beracun', 'status_aktif' => true],
            ['nama_karakteristik' => 'Korosif', 'status_aktif' => true],
        ];

        $karakteristik = [];
        foreach ($karakteristikData as $data) {
            $karakteristik[] = KarakteristikLimbah::firstOrCreate(
                ['nama_karakteristik' => $data['nama_karakteristik']],
                $data
            );
        }

        $this->command->info('   ✓ Created '.count($karakteristik)." karakteristik\n");

        return $karakteristik;
    }

    private function createKategoriKegiatan(): array
    {
        $this->command->info('📋 Creating 5 Kategori Kegiatan...');

        $kategoriData = [
            'Pembakaran Batubara',
            'Pemeliharaan Turbin',
            'Pengolahan Air',
            'Pembersihan Boiler',
            'Pemeliharaan Listrik',
        ];

        $kategori = [];
        foreach ($kategoriData as $nama) {
            $kategori[] = KategoriKegiatanSumber::firstOrCreate(
                ['nama_kategori' => $nama],
                ['nama_kategori' => $nama]
            );
        }

        $this->command->info('   ✓ Created '.count($kategori)." kategori\n");

        return $kategori;
    }

    private function createJenisLimbah(array $karakteristik): array
    {
        $this->command->info('🗑️ Creating 20 Jenis Limbah...');

        $limbahData = [
            ['kode' => 'B101', 'nama' => 'Fly Ash Batubara', 'kemasan' => 'Kantong', 'biaya' => 75000],
            ['kode' => 'B102', 'nama' => 'Bottom Ash Batubara', 'kemasan' => 'Curah', 'biaya' => 65000],
            ['kode' => 'B103', 'nama' => 'Oli Bekas Mesin', 'kemasan' => 'Drum', 'biaya' => 120000],
            ['kode' => 'B104', 'nama' => 'Kain Majun Terkontaminasi', 'kemasan' => 'Kantong', 'biaya' => 55000],
            ['kode' => 'B105', 'nama' => 'Filter Oli Bekas', 'kemasan' => 'Drum', 'biaya' => 85000],
            ['kode' => 'B106', 'nama' => 'Baterai Bekas', 'kemasan' => 'Palet', 'biaya' => 150000],
            ['kode' => 'B107', 'nama' => 'Lampu TL Bekas', 'kemasan' => 'Kotak', 'biaya' => 95000],
            ['kode' => 'B108', 'nama' => 'Sludge Lumpur IPAL', 'kemasan' => 'Drum', 'biaya' => 110000],
            ['kode' => 'B109', 'nama' => 'Cat Bekas', 'kemasan' => 'Kaleng', 'biaya' => 90000],
            ['kode' => 'B110', 'nama' => 'Solvent Bekas', 'kemasan' => 'Drum', 'biaya' => 130000],
            ['kode' => 'B111', 'nama' => 'Resin Bekas', 'kemasan' => 'Kantong', 'biaya' => 100000],
            ['kode' => 'B112', 'nama' => 'Kemasan Terkontaminasi B3', 'kemasan' => 'Palet', 'biaya' => 70000],
            ['kode' => 'B113', 'nama' => 'Asbes Bekas', 'kemasan' => 'Kantong', 'biaya' => 200000],
            ['kode' => 'B114', 'nama' => 'Limbah Laboratorium', 'kemasan' => 'Botol', 'biaya' => 180000],
            ['kode' => 'B115', 'nama' => 'Elektrolit Bekas', 'kemasan' => 'Drum', 'biaya' => 140000],
            ['kode' => 'B116', 'nama' => 'Grease Bekas', 'kemasan' => 'Drum', 'biaya' => 95000],
            ['kode' => 'B117', 'nama' => 'Coolant Bekas', 'kemasan' => 'Drum', 'biaya' => 88000],
            ['kode' => 'B118', 'nama' => 'Sarung Tangan Terkontaminasi', 'kemasan' => 'Kantong', 'biaya' => 50000],
            ['kode' => 'B119', 'nama' => 'PCB Transformer', 'kemasan' => 'Drum', 'biaya' => 250000],
            ['kode' => 'B120', 'nama' => 'Slag Batubara', 'kemasan' => 'Curah', 'biaya' => 60000],
        ];

        $jenisLimbah = [];
        foreach ($limbahData as $index => $data) {
            $karId = $karakteristik[$index % count($karakteristik)]->karakteristik_id;

            $jenisLimbah[] = JenisLimbah::firstOrCreate(
                ['kode_limbah' => $data['kode']],
                [
                    'kode_limbah' => $data['kode'],
                    'nama_limbah' => $data['nama'],
                    'kemasan' => $data['kemasan'],
                    'jumlah_ton_per_tahun' => fake()->numberBetween(10, 500),
                    'waktu_penyimpanan_hari' => fake()->randomElement([30, 60, 90, 120, 180]),
                    'batas_penyimpanan_hari' => fake()->randomElement([30, 60, 90, 120, 180]),
                    'karakteristik_id' => $karId,
                    'deskripsi_limbah' => 'Limbah '.$data['nama'].' dari kegiatan operasional',
                    'status_aktif' => true,
                    'biaya_pengangkutan_per_kg' => $data['biaya'],
                    'mulai_berlaku' => now()->subYear(),
                    'akhir_berlaku' => now()->addYears(2),
                    'keterangan_biaya' => ['dasar' => 'Per kilogram', 'pajak' => 'Sudah termasuk PPN'],
                ]
            );
        }

        $this->command->info('   ✓ Created '.count($jenisLimbah)." jenis limbah\n");

        return $jenisLimbah;
    }

    private function createPerusahaanPenghasil(): array
    {
        $this->command->info('🏢 Creating 10 Perusahaan Penghasil...');

        $perusahaanData = [
            ['nama' => 'PT Batubara Energi Nusantara', 'alamat' => 'Jl. Tambang No. 1, Kalimantan Timur'],
            ['nama' => 'PT Minyak Bumi Indonesia', 'alamat' => 'Jl. Kilang No. 2, Riau'],
            ['nama' => 'PT Kimia Farma Industri', 'alamat' => 'Jl. Farmasi No. 3, Jakarta'],
            ['nama' => 'PT Petrokimia Gresik', 'alamat' => 'Jl. Pupuk No. 4, Gresik'],
            ['nama' => 'PT Semen Padang', 'alamat' => 'Jl. Semen No. 5, Padang'],
            ['nama' => 'PT Krakatau Steel', 'alamat' => 'Jl. Baja No. 6, Cilegon'],
            ['nama' => 'PT PLN Persero', 'alamat' => 'Jl. Trunojoyo No. 135, Jakarta'],
            ['nama' => 'PT Pertamina Hulu Energi', 'alamat' => 'Jl. Medan Merdeka Timur No. 1A, Jakarta'],
            ['nama' => 'CV Mitra Lingkungan', 'alamat' => 'Jl. Hijau No. 8, Bandung'],
            ['nama' => 'PT Indo Waste Solution', 'alamat' => 'Jl. Daur Ulang No. 9, Surabaya'],
        ];

        $perusahaan = [];
        foreach ($perusahaanData as $data) {
            $perusahaan[] = PerusahaanPenghasil::firstOrCreate(
                ['nama_perusahaan' => $data['nama']],
                [
                    'nama_perusahaan' => $data['nama'],
                    'alamat_perusahaan' => $data['alamat'],
                    'telepon_perusahaan' => '021-'.fake()->numerify('#######'),
                    'email_perusahaan' => strtolower(str_replace([' ', '.'], ['', ''], explode(' ', $data['nama'])[1] ?? 'perusahaan')).'@company.co.id',
                    'status_aktif' => true,
                ]
            );
        }

        $this->command->info('   ✓ Created '.count($perusahaan)." perusahaan\n");

        return $perusahaan;
    }

    private function createUsers(array $units): array
    {
        $this->command->info('👥 Creating 50 Users...');

        $roles = [
            'Super Admin' => PeranPengguna::where('nama_peran', 'Super Admin')->first(),
            'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
            'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
            'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
            'Viewer' => PeranPengguna::where('nama_peran', 'Viewer')->first(),
        ];

        $users = ['superadmin' => [], 'admin' => [], 'supervisor' => [], 'operator' => [], 'viewer' => []];
        $password = Hash::make('password');

        // 1 Super Admin (unit_id = NULL)
        $superAdmin = PenggunaSistem::firstOrCreate(
            ['email_address' => 'superadmin@waspro.com'],
            [
                'nama_lengkap' => 'Super Administrator',
                'email_address' => 'superadmin@waspro.com',
                'kata_sandi_hash' => $password,
                'unit_id' => null,
                'aktif' => true,
                'email_verified_at' => now(),
            ]
        );
        if (! $superAdmin->peranPengguna()->where('peran_id', $roles['Super Admin']->peran_id)->exists()) {
            $superAdmin->peranPengguna()->attach($roles['Super Admin']->peran_id);
        }
        $users['superadmin'][] = $superAdmin;

        // 5 Administrators (1 per unit)
        foreach ($units as $index => $unit) {
            $email = 'admin.'.strtolower(str_replace(' ', '', $unit->kota)).'@waspro.com';
            $admin = PenggunaSistem::firstOrCreate(
                ['email_address' => $email],
                [
                    'nama_lengkap' => 'Administrator '.$unit->kota,
                    'email_address' => $email,
                    'kata_sandi_hash' => $password,
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]
            );
            if (! $admin->peranPengguna()->where('peran_id', $roles['Administrator']->peran_id)->exists()) {
                $admin->peranPengguna()->attach($roles['Administrator']->peran_id);
            }
            $users['admin'][] = $admin;
        }

        // 10 Supervisors (2 per unit)
        foreach ($units as $unit) {
            for ($i = 1; $i <= 2; $i++) {
                $email = 'supervisor.'.strtolower(str_replace(' ', '', $unit->kota)).$i.'@waspro.com';
                $supervisor = PenggunaSistem::firstOrCreate(
                    ['email_address' => $email],
                    [
                        'nama_lengkap' => 'Supervisor '.$unit->kota.' '.$i,
                        'email_address' => $email,
                        'kata_sandi_hash' => $password,
                        'unit_id' => $unit->unit_id,
                        'aktif' => true,
                        'email_verified_at' => now(),
                    ]
                );
                if (! $supervisor->peranPengguna()->where('peran_id', $roles['Supervisor']->peran_id)->exists()) {
                    $supervisor->peranPengguna()->attach($roles['Supervisor']->peran_id);
                }
                $users['supervisor'][] = $supervisor;
            }
        }

        // 30 Operators (6 per unit)
        foreach ($units as $unit) {
            for ($i = 1; $i <= 6; $i++) {
                $email = 'operator.'.strtolower(str_replace(' ', '', $unit->kota)).$i.'@waspro.com';
                $operator = PenggunaSistem::firstOrCreate(
                    ['email_address' => $email],
                    [
                        'nama_lengkap' => 'Operator '.$unit->kota.' '.$i,
                        'email_address' => $email,
                        'kata_sandi_hash' => $password,
                        'unit_id' => $unit->unit_id,
                        'aktif' => true,
                        'email_verified_at' => now(),
                    ]
                );
                if (! $operator->peranPengguna()->where('peran_id', $roles['Operator']->peran_id)->exists()) {
                    $operator->peranPengguna()->attach($roles['Operator']->peran_id);
                }
                $users['operator'][] = $operator;
            }
        }

        // 4 Viewers (1 per unit for first 4 units)
        for ($i = 0; $i < 4; $i++) {
            $unit = $units[$i];
            $email = 'viewer.'.strtolower(str_replace(' ', '', $unit->kota)).'@waspro.com';
            $viewer = PenggunaSistem::firstOrCreate(
                ['email_address' => $email],
                [
                    'nama_lengkap' => 'Viewer '.$unit->kota,
                    'email_address' => $email,
                    'kata_sandi_hash' => $password,
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]
            );
            if (! $viewer->peranPengguna()->where('peran_id', $roles['Viewer']->peran_id)->exists()) {
                $viewer->peranPengguna()->attach($roles['Viewer']->peran_id);
            }
            $users['viewer'][] = $viewer;
        }

        $totalUsers = count($users['superadmin']) + count($users['admin']) + count($users['supervisor']) + count($users['operator']) + count($users['viewer']);
        $this->command->info("   ✓ Created {$totalUsers} users (1 Super Admin, 5 Administrator, 10 Supervisor, 30 Operator, 4 Viewer)\n");

        return $users;
    }

    private function createLogPenyimpanan(array $units, array $users, array $jenisLimbah, array $perusahaan, array $kategori): void
    {
        $this->command->info('📦 Creating 200 Log Penyimpanan Limbah...');

        // Collect all operators and supervisors for approvals
        $allOperators = array_merge($users['operator'], $users['supervisor'], $users['admin']);
        $approvers = array_merge($users['supervisor'], $users['admin']);

        $rejectionReasons = [
            'Data jumlah limbah tidak sesuai dengan pengukuran lapangan',
            'Dokumen pendukung tidak lengkap',
            'Kode limbah tidak sesuai dengan jenis limbah aktual',
            'Tanggal masuk tidak valid',
            'Perusahaan penghasil tidak terdaftar dalam sistem',
            'Informasi sumber kegiatan tidak jelas',
            'Kemasan limbah tidak sesuai standar',
            'Perlu verifikasi ulang oleh tim lapangan',
        ];

        $createdCount = 0;

        // Distribution: 80 Tersimpan, 70 Diangkut, 50 Expired
        $statusDistribution = [
            ['status' => 'Tersimpan', 'count' => 80],
            ['status' => 'Diangkut', 'count' => 70],
            ['status' => 'Kadaluarsa', 'count' => 50],
        ];

        // Approval distribution: 100 Pending, 60 Approved, 40 Rejected
        $approvalQueue = array_merge(
            array_fill(0, 100, 'pending'),
            array_fill(0, 60, 'approved'),
            array_fill(0, 40, 'rejected')
        );
        shuffle($approvalQueue);
        $approvalIndex = 0;

        foreach ($statusDistribution as $statusConfig) {
            for ($i = 0; $i < $statusConfig['count']; $i++) {
                $unit = $units[array_rand($units)];
                $jenis = $jenisLimbah[array_rand($jenisLimbah)];
                $perus = $perusahaan[array_rand($perusahaan)];
                $kat = $kategori[array_rand($kategori)];

                // Find an operator from this unit or any operator
                $unitOperators = array_filter($allOperators, fn ($op) => $op->unit_id === $unit->unit_id);
                $creator = ! empty($unitOperators) ? $unitOperators[array_rand($unitOperators)] : $allOperators[array_rand($allOperators)];

                $faker = fake();
                $tanggalMasuk = $faker->dateTimeBetween('-6 months', 'now');
                $expiryDays = $jenis->waktu_penyimpanan_hari ?? 90;
                $tanggalKadaluarsa = (clone $tanggalMasuk)->modify("+{$expiryDays} days");
                $jumlahMasuk = $faker->randomFloat(2, 0.5, 100);

                // Calculate expiry status
                $now = now();
                $daysUntilExpiry = $now->diffInDays($tanggalKadaluarsa, false);
                if ($statusConfig['status'] === 'Kadaluarsa') {
                    $expiryStatus = 'Expired';
                    $tanggalKadaluarsa = $faker->dateTimeBetween('-30 days', '-1 day');
                    $tanggalMasuk = (clone $tanggalKadaluarsa)->modify("-{$expiryDays} days");
                } elseif ($daysUntilExpiry < 0) {
                    $expiryStatus = 'Expired';
                } elseif ($daysUntilExpiry <= 7) {
                    $expiryStatus = 'Critical';
                } elseif ($daysUntilExpiry <= 30) {
                    $expiryStatus = 'Warning';
                } else {
                    $expiryStatus = 'Safe';
                }

                $tanggalPengangkutan = null;
                $jumlahDiangkut = 0;
                if ($statusConfig['status'] === 'Diangkut') {
                    $tanggalPengangkutan = $faker->dateTimeBetween($tanggalMasuk, 'now');
                    $jumlahDiangkut = $faker->randomFloat(2, $jumlahMasuk * 0.5, $jumlahMasuk);
                }

                // Get approval status
                $approvalStatus = $approvalQueue[$approvalIndex % count($approvalQueue)] ?? 'pending';
                $approvalIndex++;

                $approvedBy = null;
                $approvedAt = null;
                $rejectedReason = null;

                if ($approvalStatus === 'approved') {
                    $approvedBy = $approvers[array_rand($approvers)]->user_id;
                    $approvedAt = $faker->dateTimeBetween($tanggalMasuk, 'now');
                } elseif ($approvalStatus === 'rejected') {
                    $approvedBy = $approvers[array_rand($approvers)]->user_id;
                    $approvedAt = $faker->dateTimeBetween($tanggalMasuk, 'now');
                    $rejectedReason = $rejectionReasons[array_rand($rejectionReasons)];
                }

                LogPenyimpananLimbah::create([
                    'tanggal_limbah_masuk' => $tanggalMasuk->format('Y-m-d'),
                    'detail_sumber_limbah' => $kat->nama_kategori.' - '.$faker->sentence(5),
                    'jumlah_limbah_masuk' => round($jumlahMasuk, 2),
                    'maksimal_penyimpanan_tanggal' => $tanggalKadaluarsa instanceof \DateTime ? $tanggalKadaluarsa->format('Y-m-d') : $tanggalKadaluarsa,
                    'status_log' => $statusConfig['status'],
                    'tanggal_pengangkutan' => $tanggalPengangkutan?->format('Y-m-d'),
                    'jumlah_diangkut' => round($jumlahDiangkut, 2),
                    'user_id' => $creator->user_id,
                    'kode_limbah' => $jenis->kode_limbah,
                    'perusahaan_id' => $perus->perusahaan_id,
                    'unit_id' => $unit->unit_id,
                    'tanggal_kadaluarsa' => $tanggalKadaluarsa instanceof \DateTime ? $tanggalKadaluarsa->format('Y-m-d') : $tanggalKadaluarsa,
                    'expiry_status' => $expiryStatus,
                    'approval_status' => $approvalStatus,
                    'approved_by' => $approvedBy,
                    'approved_at' => $approvedAt?->format('Y-m-d H:i:s'),
                    'rejected_reason' => $rejectedReason,
                ]);

                $createdCount++;
            }
        }

        $this->command->info("   ✓ Created {$createdCount} logs (80 Tersimpan, 70 Diangkut, 50 Expired)\n");
        $this->command->info("   ✓ Approval distribution: 100 Pending, 60 Approved, 40 Rejected\n");
    }

    private function createAuditLogs(array $users): void
    {
        $this->command->info('📝 Creating 150 Audit Logs...');

        $allUsers = array_merge(
            $users['superadmin'],
            $users['admin'],
            $users['supervisor'],
            $users['operator']
        );

        $faker = fake();

        // 30 create operations
        for ($i = 0; $i < 30; $i++) {
            AuditLog::factory()->createAction()->create([
                'user_id' => $allUsers[array_rand($allUsers)]->user_id,
                'table_name' => $faker->randomElement(['log_penyimpanan_limbah', 'pengguna_sistem', 'jenis_limbah']),
            ]);
        }

        // 90 update operations
        for ($i = 0; $i < 90; $i++) {
            AuditLog::factory()->update()->create([
                'user_id' => $allUsers[array_rand($allUsers)]->user_id,
                'table_name' => $faker->randomElement(['log_penyimpanan_limbah', 'pengguna_sistem', 'jenis_limbah', 'unit_pembangkit']),
            ]);
        }

        // 30 delete operations
        for ($i = 0; $i < 30; $i++) {
            AuditLog::factory()->delete()->create([
                'user_id' => $allUsers[array_rand($allUsers)]->user_id,
                'table_name' => $faker->randomElement(['log_penyimpanan_limbah', 'jenis_limbah']),
            ]);
        }

        $this->command->info("   ✓ Created 150 audit logs (30 create, 90 update, 30 delete)\n");
    }

    private function printSummary(array $units, array $jenisLimbah, array $users): void
    {
        $this->command->info("\n═══════════════════════════════════════════════════");
        $this->command->info('✅ WASPRO Dummy Data Generation Complete!');
        $this->command->info("═══════════════════════════════════════════════════\n");

        $this->command->info('📊 Summary:');
        $this->command->info('   - Unit Pembangkit: '.count($units));
        $this->command->info('   - Jenis Limbah: '.count($jenisLimbah));
        $this->command->info('   - Users: '.(count($users['superadmin']) + count($users['admin']) + count($users['supervisor']) + count($users['operator']) + count($users['viewer'])));
        $this->command->info('   - Log Penyimpanan: 200');
        $this->command->info("   - Audit Logs: 150\n");

        $this->command->info("🔑 Login Credentials (password: 'password' for all users):");
        $this->command->info('   Super Admin: superadmin@waspro.com');
        $this->command->info('   Admin Jakarta: admin.jakarta@waspro.com');
        $this->command->info('   Supervisor Jakarta: supervisor.jakarta1@waspro.com');
        $this->command->info("   Operator Jakarta: operator.jakarta1@waspro.com\n");

        $this->command->info('📍 Units Created:');
        foreach ($units as $unit) {
            $this->command->info("   - {$unit->nama_unit} ({$unit->kota})");
        }
        $this->command->info('');
    }
}
