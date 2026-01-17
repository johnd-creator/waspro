<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\PenggunaSistem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $user = PenggunaSistem::where('aktif', true)->inRandomOrder()->first()
            ?? PenggunaSistem::factory()->create();

        $tables = [
            'log_penyimpanan_limbah',
            'pengguna_sistem',
            'unit_pembangkit',
            'jenis_limbah',
            'perusahaan_penghasil',
        ];

        $tableName = $this->faker->randomElement($tables);
        $action = $this->faker->randomElement(['create', 'update', 'delete']);

        $oldValue = $action !== 'create' ? [
            'status' => 'Tersimpan',
            'jumlah' => $this->faker->randomFloat(2, 1, 100),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
        ] : null;

        $newValue = $action !== 'delete' ? [
            'status' => $this->faker->randomElement(['Tersimpan', 'Diangkut']),
            'jumlah' => $this->faker->randomFloat(2, 1, 100),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ] : null;

        return [
            'user_id' => $user->user_id,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $this->faker->numberBetween(1, 1000),
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }

    public function createAction(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'create',
            'old_value' => null,
            'new_value' => [
                'status' => 'Tersimpan',
                'jumlah' => $this->faker->randomFloat(2, 1, 100),
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function update(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'update',
            'old_value' => [
                'status' => 'Tersimpan',
                'jumlah' => $this->faker->randomFloat(2, 1, 50),
            ],
            'new_value' => [
                'status' => 'Diangkut',
                'jumlah' => $this->faker->randomFloat(2, 1, 50),
            ],
        ]);
    }

    public function delete(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'delete',
            'old_value' => [
                'status' => 'Tersimpan',
                'jumlah' => $this->faker->randomFloat(2, 1, 100),
            ],
            'new_value' => null,
        ]);
    }

    public function forTable(string $tableName): static
    {
        return $this->state(fn (array $attributes) => [
            'table_name' => $tableName,
        ]);
    }
}
