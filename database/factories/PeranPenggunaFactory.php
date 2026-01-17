<?php

namespace Database\Factories;

use App\Models\PeranPengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PeranPengguna>
 */
class PeranPenggunaFactory extends Factory
{
    protected $model = PeranPengguna::class;

    public function definition(): array
    {
        $roles = [
            [
                'nama' => 'Administrator',
                'deskripsi' => 'Akses penuh ke seluruh sistem',
            ],
            [
                'nama' => 'Operator',
                'deskripsi' => 'Dapat mengelola data limbah dan laporan',
            ],
            [
                'nama' => 'Supervisor',
                'deskripsi' => 'Dapat menyetujui dan memverifikasi data',
            ],
            [
                'nama' => 'Viewer',
                'deskripsi' => 'Hanya dapat melihat data tanpa mengubah',
            ],
            [
                'nama' => 'Auditor',
                'deskripsi' => 'Dapat mengakses log audit dan laporan',
            ],
        ];

        $role = $this->faker->randomElement($roles);

        return [
            'nama_peran' => $role['nama'],
            'deskripsi' => $role['deskripsi'],
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function administrator(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_peran' => 'Administrator',
            'deskripsi' => 'Akses penuh ke seluruh sistem',
            'is_active' => true,
        ]);
    }

    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_peran' => 'Operator',
            'deskripsi' => 'Dapat mengelola data limbah dan laporan',
            'is_active' => true,
        ]);
    }

    public function supervisor(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_peran' => 'Supervisor',
            'deskripsi' => 'Dapat menyetujui dan memverifikasi data',
            'is_active' => true,
        ]);
    }

    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_peran' => 'Viewer',
            'deskripsi' => 'Hanya dapat melihat data tanpa mengubah',
            'is_active' => true,
        ]);
    }
}
