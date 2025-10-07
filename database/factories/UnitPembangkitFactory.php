<?php

namespace Database\Factories;

use App\Models\UnitPembangkit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UnitPembangkit>
 */
class UnitPembangkitFactory extends Factory
{
    protected $model = UnitPembangkit::class;

    public function definition(): array
    {
        return [
            'nama_unit' => $this->faker->company().' Unit',
            'alamat_unit' => $this->faker->address(),
            'kota' => $this->faker->city(),
            'kode_pos' => $this->faker->postcode(),
            'telepon_unit' => $this->faker->phoneNumber(),
            'keterangan' => $this->faker->sentence(),
            'status_aktif' => $this->faker->boolean(90),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status_aktif' => false]);
    }
}
