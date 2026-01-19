<?php

namespace Database\Seeders;

use App\Models\KarakteristikLimbah;
use Illuminate\Database\Seeder;

class KarakteristikLimbahSeeder extends Seeder
{
    public function run(): void
    {
        $karakteristik = [
            'B3 Berbahaya',
            'B3 Mudah Terbakar',
            'B3 Reaktif',
            'B3 Korosif',
            'B3 Infeksius',
            'Non B3',
            'Radioaktif',
            'B3 Toksik',
        ];

        foreach ($karakteristik as $nama) {
            KarakteristikLimbah::create([
                'nama_karakteristik' => $nama,
                'status_aktif' => true,
            ]);
        }

        $this->command->info('✓ '.count($karakteristik).' karakteristik limbah berhasil dibuat');
    }
}
