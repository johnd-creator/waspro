<?php

namespace Database\Factories;

use App\Models\KategoriKegiatanSumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriKegiatanSumber>
 */
class KategoriKegiatanSumberFactory extends Factory
{
    protected $model = KategoriKegiatanSumber::class;

    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->unique()->words(2, true),
        ];
    }
}
