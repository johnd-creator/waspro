<?php

namespace Database\Seeders;

use App\Models\LogPenyimpananLimbah;
use Illuminate\Database\Seeder;

class LogPenyimpananLimbahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $targetTotal = 100;
        $existingCount = LogPenyimpananLimbah::count();

        if ($existingCount >= $targetTotal) {
            $this->command?->warn("Log penyimpanan limbah sudah memiliki {$existingCount} baris, tidak ada data tambahan yang dibuat.");

            return;
        }

        $remaining = $targetTotal - $existingCount;

        $tersimpanCount = (int) floor($remaining * 0.4);
        $diangkutCount = (int) floor($remaining * 0.35);
        $expiredCount = $remaining - $tersimpanCount - $diangkutCount;

        // Pastikan setiap kategori mendapat minimal satu data bila memungkinkan
        if ($remaining >= 3) {
            if ($tersimpanCount === 0) {
                $tersimpanCount = 1;
                $expiredCount--;
            }

            if ($diangkutCount === 0) {
                $diangkutCount = 1;
                $expiredCount--;
            }
        }

        $this->command?->info("Membuat {$remaining} data log penyimpanan limbah untuk mencapai {$targetTotal} baris ...");

        if ($tersimpanCount > 0) {
            LogPenyimpananLimbah::factory()
                ->count($tersimpanCount)
                ->tersimpan()
                ->create();
        }

        if ($diangkutCount > 0) {
            LogPenyimpananLimbah::factory()
                ->count($diangkutCount)
                ->diangkut()
                ->create();
        }

        if ($expiredCount > 0) {
            LogPenyimpananLimbah::factory()
                ->count($expiredCount)
                ->expired()
                ->create();
        }

        $this->command?->info('✓ Data log penyimpanan limbah selesai dibuat.');
    }
}
