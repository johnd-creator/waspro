<?php

namespace Database\Factories;

use App\Models\PerusahaanPenghasil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PerusahaanPenghasil>
 */
class PerusahaanPenghasilFactory extends Factory
{
    protected $model = PerusahaanPenghasil::class;

    public function definition(): array
    {
        return [
            'nama_perusahaan' => 'PT '.$this->faker->unique()->company(),
            'jenis_perusahaan' => $this->faker->randomElement(['Industri', 'Laboratorium', 'Rumah Sakit']),
            'npwp' => $this->faker->bothify('##.###.###.#-###.###'),
            'telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'kota' => $this->faker->city(),
            'alamat_perusahaan' => $this->faker->address(),
            'person_in_charge' => $this->faker->name(),
            'status_aktif' => $this->faker->boolean(90),
            'keterangan' => $this->faker->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status_aktif' => false]);
    }
}
