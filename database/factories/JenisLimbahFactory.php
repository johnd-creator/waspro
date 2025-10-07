<?php

namespace Database\Factories;

use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JenisLimbah>
 */
class JenisLimbahFactory extends Factory
{
    protected $model = JenisLimbah::class;

    public function definition(): array
    {
        return [
            'kode_limbah' => Str::upper('LMB-'.$this->faker->unique()->bothify('###')),
            'nama_limbah' => $this->faker->words(3, true),
            'kemasan' => $this->faker->randomElement(['Drum', 'Kantong', 'Kontainer']),
            'jumlah_ton_per_tahun' => $this->faker->randomFloat(2, 0.5, 20),
            'waktu_penyimpanan_hari' => $this->faker->numberBetween(10, 120),
            'batas_penyimpanan_hari' => $this->faker->numberBetween(30, 180),
            'karakteristik_id' => KarakteristikLimbah::factory(),
            'deskripsi_limbah' => $this->faker->sentence(),
            'status_aktif' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status_aktif' => false]);
    }
}
