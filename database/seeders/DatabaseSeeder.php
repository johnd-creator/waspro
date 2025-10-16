<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            LogPenyimpananLimbahSeeder::class,
            ApplicationSettingSeeder::class,
        ]);
    }
}
