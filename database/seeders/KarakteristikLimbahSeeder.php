<?php

namespace Database\Seeders;

use App\Models\KarakteristikLimbah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KarakteristikLimbahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karakteristik = [
            ['nama_karakteristik' => 'Mudah Meledak'],
            ['nama_karakteristik' => 'Pengoksidasi'],
            ['nama_karakteristik' => 'Mudah Terbakar'],
            ['nama_karakteristik' => 'Beracun'],
            ['nama_karakteristik' => 'Berbahaya Bagi Lingkungan'],
            ['nama_karakteristik' => 'Korosif'],
            ['nama_karakteristik' => 'Bersifat Infeksius'],
            ['nama_karakteristik' => 'Reaktif'],
        ];

        foreach ($karakteristik as $item) {
            KarakteristikLimbah::firstOrCreate(
                ['nama_karakteristik' => $item['nama_karakteristik']]
            );
        }
    }
}