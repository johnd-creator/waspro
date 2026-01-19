<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use App\Models\KategoriKegiatanSumber;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UnitPembangkitSeeder::class,
            PeranPenggunaSeeder::class,
            PenggunaSistemSeeder::class,
            KarakteristikLimbahSeeder::class,
            KategoriKegiatanSumberSeeder::class,
            PerusahaanPenghasilSeeder::class,
            JenisLimbahSeeder::class,
        ]);
    }
}
