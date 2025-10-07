<?php

namespace Database\Factories;

use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenggunaSistem>
 */
class PenggunaSistemFactory extends Factory
{
    protected $model = PenggunaSistem::class;

    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name(),
            'email_address' => $this->faker->unique()->safeEmail(),
            'kata_sandi_hash' => bcrypt('password'),
            'unit_id' => UnitPembangkit::factory(),
            'aktif' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['aktif' => false]);
    }
}
