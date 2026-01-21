<?php

namespace Database\Factories;

use App\Models\KarakteristikLimbah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KarakteristikLimbah>
 */
class KarakteristikLimbahFactory extends Factory
{
    protected $model = KarakteristikLimbah::class;

    public function definition(): array
    {
        return [
            'nama_karakteristik' => $this->faker->randomElement(['Korosif', 'Reaktif', 'Berbahaya', 'Beracun']) . ' ' . $this->faker->randomNumber(3),
            'status_aktif' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn() => ['status_aktif' => false]);
    }
}
