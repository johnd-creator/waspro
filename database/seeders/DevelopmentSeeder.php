<?php

namespace Database\Seeders;

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
 * Development Testing Data Seeder
 *
 * Seeder ini membuat sample data dalam jumlah banyak untuk tujuan testing pengembangan.
 * Jalankan dengan: php artisan db:seed --class=DevelopmentSeeder
 *
 * Data yang dibuat:
 * - 50+ Log Penyimpanan Limbah dengan berbagai status
 * - 30+ Jenis Limbah
 * - 20+ Pengguna Sistem
 * - 10+ Perusahaan Penghasil
 * - 10+ Kategori Kegiatan Sumber
 */
class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->newLine();
        $this->command->info('=== DEVELOPMENT TEST DATA SEEDER ===');
        $this->command->newLine();

        $this->createAdditionalUsers(20);
        $this->createAdditionalWasteTypes(30);
        $this->createAdditionalCategories(10);
        $this->createAdditionalCompanies(10);
        $this->createStorageLogs(50);

        $this->command->newLine();
        $this->command->info('✓ Development test data berhasil dibuat!');
        $this->command->newLine();
        $this->command->info('Total Data:');
        $this->command->info('  - Pengguna Sistem: '.PenggunaSistem::count());
        $this->command->info('  - Jenis Limbah: '.JenisLimbah::count());
        $this->command->info('  - Kategori Kegiatan: '.KategoriKegiatanSumber::count());
        $this->command->info('  - Perusahaan: '.PerusahaanPenghasil::count());
        $this->command->info('  - Log Penyimpanan: '.LogPenyimpananLimbah::count());
        $this->command->newLine();
    }

    protected function createAdditionalUsers(int $count): void
    {
        $this->command->info('Membuat '.$count.' pengguna tambahan untuk testing...');

        $units = UnitPembangkit::all();
        $peranList = PeranPengguna::all();
        $adminPeran = $peranList->where('nama_peran', 'Administrator')->first();
        $supervisorPeran = $peranList->where('nama_peran', 'Supervisor')->first();
        $operatorPeran = $peranList->where('nama_peran', 'Operator')->first();
        $viewerPeran = $peranList->where('nama_peran', 'Viewer')->first();

        $names = [
            'Ahmad Wijaya', 'Budi Santoso', 'Citra Dewi', 'Dimas Pratama',
            'Eka Putri', 'Fajar Nugraha', 'Gita Savitri', 'Hendra Kusuma',
            'Indah Permata', 'Joko Susilo', 'Kartika Sari', 'Lukman Hakim',
            'Maya Sari', 'Nurul Izzah', 'Oki Setiawan', 'Putri Ayu',
            'Rizky Hidayat', 'Siti Nurhaliza', 'Taufik Hidayat', 'Umar Faruk',
            'Vera Kristina', 'Wahyu Nugroho', 'Yuli Astuti', 'Zainal Abidin',
            'Adi Surya', 'Bella Putri', 'Cahyo Purnomo', 'Dian Kusuma',
            'Eko Prasetyo', 'Fitri Handayani',
        ];

        $roles = ['Administrator', 'Supervisor', 'Operator', 'Viewer'];
        $emails = [
            'admin', 'supervisor', 'operator', 'viewer',
            'manager', 'staff', 'koordinator', 'analis',
            'pengawas', 'petugas',
        ];

        for ($i = 0; $i < $count; $i++) {
            $name = $names[$i % count($names)].' '.$i;
            $role = $roles[$i % count($roles)];
            $emailPrefix = $emails[$i % count($emails)];
            $email = $emailPrefix.$i.'@test.dev';

            if (PenggunaSistem::where('email_address', $email)->exists()) {
                continue;
            }

            $peran = match ($role) {
                'Administrator' => $adminPeran,
                'Supervisor' => $supervisorPeran,
                'Operator' => $operatorPeran,
                'Viewer' => $viewerPeran,
                default => $operatorPeran,
            };

            $user = PenggunaSistem::create([
                'nama_lengkap' => $name,
                'email_address' => $email,
                'kata_sandi_hash' => Hash::make('password123'),
                'unit_id' => $units->random()->unit_id ?? null,
                'aktif' => $i % 10 !== 0,
                'email_verified_at' => now(),
            ]);

            if ($peran) {
                $user->peranPengguna()->attach($peran->peran_id);
            }

            $this->command->info("  ✓ User: {$name} ({$email}) - {$role}");
        }
    }

    protected function createAdditionalWasteTypes(int $count): void
    {
        $this->command->info('Membuat '.$count.' jenis limbah tambahan...');

        $karakteristikList = KarakteristikLimbah::all();
        $kategoriList = KategoriKegiatanSumber::all();

        $wasteTypes = [
            'Baterai Bekas', 'Cat Kaleng', 'Deterjen Bekas', 'Elektronik Rusak',
            'Fotokopi Bekas', 'Genset Bekas', 'Hand sanitizer Bekas', 'Ink Toner Bekas',
            'Jarum Bekas', 'Kabel Bekas', 'Lampu Pijar Bekas', 'Minyak Bekas',
            'Neon Bekas', 'Oli Bekas', 'Printer Bekas', 'Query Sampah',
            'Radiator Bekas', 'Sparepart Bekas', 'Thermometer Bekas', 'UPS Bekas',
            'Ventilator Bekas', 'Water Heater Bekas', 'X-ray Bekas', 'Yield Bekas',
            'Zinc Bekas', 'Aluminium', 'Besi', 'Kaca', 'Kertas',
            'Plastik', 'Tembaga', 'Timbal', 'Seng',
        ];

        $codes = ['BAT', 'CTK', 'DTR', 'ELK', 'FTK', 'GNS', 'HND', 'INK', 'JRM', 'KBL'];

        for ($i = 0; $i < $count; $i++) {
            $name = $wasteTypes[$i % count($wasteTypes)].' '.($i + 1);
            $code = $codes[$i % count($codes)].'-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT);

            $karakteristik = $karakteristikList->random();
            $kategori = $kategoriList->random();

            if (JenisLimbah::where('kode_limbah', $code)->exists()) {
                continue;
            }

            JenisLimbah::create([
                'kode_limbah' => $code,
                'nama_limbah' => $name,
                'kemasan' => 'Drum',
                'jumlah_ton_per_tahun' => rand(1, 100),
                'karakteristik_id' => $karakteristik?->karakteristik_id,
                'kategori_id' => $kategori?->kategori_id,
                'waktu_penyimpanan_hari' => rand(7, 180),
                'status_aktif' => $i % 10 !== 0,
                'biaya_pengangkutan_per_kg' => rand(1000, 50000),
            ]);
        }

        $this->command->info("  ✓ {$count} jenis limbah dibuat");
    }

    protected function createAdditionalCategories(int $count): void
    {
        $this->command->info('Membuat '.$count.' kategori tambahan...');

        $categories = [
            'Kegiatan Laboratorium', 'Kegiatan Perawatan', 'Kegiatan Administrasi',
            'Kegiatan Operasional', 'Kegiatan Kebersihan', 'Kegiatan Maintenance',
            'Kegiatan Konstruksi', 'Kegiatan Demolisi', 'Kegiatan Pengiriman',
            'Kegiatan Penyimpanan',
        ];

        for ($i = 0; $i < $count; $i++) {
            KategoriKegiatanSumber::updateOrCreate(
                ['nama_kategori' => $categories[$i % count($categories)].' '.($i + 1)],
                []
            );
        }

        $this->command->info("  ✓ {$count} kategori dibuat");
    }

    protected function createAdditionalCompanies(int $count): void
    {
        $this->command->info('Membuat '.$count.' perusahaan tambahan...');

        $companies = [
            'PT. Enviro Waste', 'CV. Green Earth', 'PT. Clean Indonesia',
            'CV. Eco Solution', 'PT. Waste Management', 'CV. Recycle Pro',
            'PT. Bio Energy', 'CV. Organic Care', 'PT. Solar Power',
            'CV. Hydro Energy',
        ];

        $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Yogyakarta'];

        for ($i = 0; $i < $count; $i++) {
            $city = $cities[$i % count($cities)];
            $company = $companies[$i % count($companies)];

            $namaPerusahaan = $company.' '.($i + 1);

            if (PerusahaanPenghasil::where('nama_perusahaan', $namaPerusahaan)->exists()) {
                continue;
            }

            PerusahaanPenghasil::create([
                'nama_perusahaan' => $namaPerusahaan,
                'jenis_perusahaan' => ['Makanan', 'Tekstil', 'Elektronik', 'Farmasi', 'Kimia'][$i % 5],
                'alamat_perusahaan' => "Jl. Contoh No. ".($i + 1).", {$city}",
                'kota' => $city,
                'telepon' => '021-'.str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT).str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '', $company)).($i + 1).'@example.com',
                'status_aktif' => $i % 10 !== 0,
            ]);
        }

        $this->command->info("  ✓ {$count} perusahaan dibuat");
    }

    protected function createStorageLogs(int $count): void
    {
        $this->command->info('Membuat '.$count.' log penyimpanan...');

        $users = PenggunaSistem::where('aktif', true)->get();
        $wasteTypes = JenisLimbah::where('status_aktif', true)->get();
        $companies = PerusahaanPenghasil::where('status_aktif', true)->get();
        $units = UnitPembangkit::all();

        $tasks = [
            'Penggantian oli mesin', 'Perawatan rutin generator', 'Pembersihan tangki',
            'Penggantian filter udara', 'Inspeksi kabel', 'Penggantian lampu',
            'Perbaikan pompa', 'Pembersihan panel', 'Penggantian bearing',
            'Perawatan trafo', 'Pembersihan area', 'Penggantian seal',
            'Inspeksi pipa', 'Penggantian valve', 'Pembersihan cooling tower',
        ];

        $statuses = ['Tersimpan', 'Diangkut', 'Kadaluarsa'];

        for ($i = 0; $i < $count; $i++) {
            $wasteType = $wasteTypes->random();
            $storageDays = $wasteType->waktu_penyimpanan_hari ?? 30;
            $entryDate = now()->subDays(rand(0, 90));
            $expiryDate = $entryDate->copy()->addDays($storageDays);

            $status = $statuses[$i % count($statuses)];

            if ($status === 'Kadaluarsa') {
                $entryDate = now()->subDays($storageDays + rand(1, 30));
            } elseif ($status === 'Diangkut') {
                $entryDate = now()->subDays(rand(1, $storageDays));
            }

            LogPenyimpananLimbah::create([
                'kode_identitas' => 'LOG-'.date('Ymd').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tanggal_limbah_masuk' => $entryDate,
                'detail_sumber_limbah' => $tasks[$i % count($tasks)],
                'jumlah_limbah_masuk' => rand(1, 500),
                'maksimal_penyimpanan_tanggal' => $expiryDate,
                'status_log' => $status,
                'tanggal_pengangkutan' => $status === 'Diangkut' ? now()->subDays(rand(0, 10)) : null,
                'jumlah_diangkut' => $status === 'Diangkut' ? rand(1, 500) : 0,
                'user_id' => $users->random()?->user_id ?? $users->first()->user_id,
                'kode_limbah' => $wasteType->kode_limbah,
                'perusahaan_id' => $companies->random()?->perusahaan_id ?? null,
                'unit_id' => $units->random()?->unit_id ?? $units->first()->unit_id,
                'tanggal_kadaluarsa' => $expiryDate,
            ]);
        }

        $this->command->info("  ✓ {$count} log penyimpanan dibuat");
        $this->command->info('    Status:');
        $this->command->info('    - Tersimpan: '.LogPenyimpananLimbah::where('status_log', 'Tersimpan')->count());
        $this->command->info('    - Diangkut: '.LogPenyimpananLimbah::where('status_log', 'Diangkut')->count());
        $this->command->info('    - Kadaluarsa: '.LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')->count());
    }
}
