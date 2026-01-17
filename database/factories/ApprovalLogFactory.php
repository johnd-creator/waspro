<?php

namespace Database\Factories;

use App\Models\ApprovalLog;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApprovalLog>
 */
class ApprovalLogFactory extends Factory
{
    protected $model = ApprovalLog::class;

    public function definition(): array
    {
        $log = LogPenyimpananLimbah::inRandomOrder()->first() ?? LogPenyimpananLimbah::factory()->create();

        $approver = PenggunaSistem::where('aktif', true)->inRandomOrder()->first()
            ?? PenggunaSistem::factory()->create();

        $action = $this->faker->randomElement(['approve', 'reject']);

        return [
            'log_id' => $log->log_id,
            'approved_by' => $approver->user_id,
            'action' => $action,
            'rejected_reason' => $action === 'reject'
                ? $this->faker->randomElement([
                    'Data tidak lengkap',
                    'Dokumen tidak sesuai',
                    'Informasi limbah tidak valid',
                    'Perlu verifikasi ulang',
                    'Tanggal tidak sesuai dengan dokumen',
                ])
                : null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'approve',
            'rejected_reason' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'reject',
            'rejected_reason' => $this->faker->randomElement([
                'Data tidak lengkap',
                'Dokumen tidak sesuai',
                'Informasi limbah tidak valid',
                'Perlu verifikasi ulang',
                'Tanggal tidak sesuai dengan dokumen',
                'Jumlah limbah tidak sesuai',
            ]),
        ]);
    }
}
