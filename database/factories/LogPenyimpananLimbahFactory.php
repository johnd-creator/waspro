<?php

namespace Database\Factories;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<LogPenyimpananLimbah>
 */
class LogPenyimpananLimbahFactory extends Factory
{
    protected $model = LogPenyimpananLimbah::class;

    public function definition(): array
    {
        $jenisLimbah = JenisLimbah::inRandomOrder()->first() ?? JenisLimbah::factory()->create();

        $unit = UnitPembangkit::inRandomOrder()->first() ?? UnitPembangkit::factory()->create();

        $user = PenggunaSistem::where('aktif', true)->inRandomOrder()->first();
        if (! $user) {
            $user = PenggunaSistem::factory()->create(['unit_id' => $unit->unit_id]);
        } elseif (! $user->unit_id) {
            $user->unit_id = $unit->unit_id;
            $user->save();
        } else {
            $unit = UnitPembangkit::find($user->unit_id) ?? $unit;
        }

        $unitId = $user->unit_id ?? $unit->unit_id;

        $perusahaan = null;
        if (PerusahaanPenghasil::exists() && $this->faker->boolean(70)) {
            $perusahaan = PerusahaanPenghasil::inRandomOrder()->first();
        } elseif ($this->faker->boolean(40)) {
            $perusahaan = PerusahaanPenghasil::factory()->create();
        }

        $now = Carbon::now();
        $expiryStatus = Arr::random(['Safe', 'Warning', 'Critical', 'Expired']);

        $tanggalKadaluarsa = match ($expiryStatus) {
            'Warning' => $now->copy()->addDays($this->faker->numberBetween(4, 7)),
            'Critical' => $now->copy()->addDays($this->faker->numberBetween(0, 3)),
            'Expired' => $now->copy()->subDays($this->faker->numberBetween(1, 10)),
            default => $now->copy()->addDays($this->faker->numberBetween(8, 45)),
        };

        $expiryDays = $jenisLimbah->batas_penyimpanan_hari
            ?? $jenisLimbah->waktu_penyimpanan_hari
            ?? $this->faker->numberBetween(30, 120);

        $tanggalMasuk = (clone $tanggalKadaluarsa)->subDays($expiryDays);
        if ($tanggalMasuk->greaterThan($now)) {
            $tanggalMasuk = $now->copy()->subDays($this->faker->numberBetween(1, 30));
            $tanggalKadaluarsa = (clone $tanggalMasuk)->addDays($expiryDays);
        }

        $maksimalPenyimpanan = (clone $tanggalMasuk)->addDays($expiryDays);

        $statusLog = $this->faker->randomElement(['Tersimpan', 'Tersimpan', 'Diangkut']);
        $jumlahMasuk = $this->faker->randomFloat(2, 0.5, 50);

        $tanggalPengangkutan = null;
        $jumlahDiangkut = 0;
        if ($statusLog === 'Diangkut') {
            $transportLimit = $tanggalKadaluarsa->greaterThan($now) ? $now : $tanggalKadaluarsa;
            $transportDate = $this->faker->dateTimeBetween($tanggalMasuk, $transportLimit);
            $tanggalPengangkutan = Carbon::instance($transportDate)->format('Y-m-d');
            $jumlahDiangkut = $this->faker->randomFloat(2, max(0.1, $jumlahMasuk * 0.5), $jumlahMasuk);
        } else {
            $tanggalPengangkutan = null;
            $jumlahDiangkut = 0;
        }

        $dokumen = $this->faker->boolean(35) ? [
            'dokumen_path' => 'documents/'.$this->faker->uuid().'.pdf',
            'dokumen_original_name' => Str::title($this->faker->words(3, true)).' '.$this->faker->randomNumber(3).'.pdf',
            'dokumen_mime' => 'application/pdf',
            'dokumen_size' => $this->faker->numberBetween(80_000, 5_000_000),
            'dokumen_uploaded_at' => Carbon::instance(
                $this->faker->dateTimeBetween($tanggalMasuk, $now)
            )->format('Y-m-d H:i:s'),
        ] : [
            'dokumen_path' => null,
            'dokumen_original_name' => null,
            'dokumen_mime' => null,
            'dokumen_size' => null,
            'dokumen_uploaded_at' => null,
        ];

        return array_merge([
            'kode_identitas' => null,
            'tanggal_limbah_masuk' => $tanggalMasuk->format('Y-m-d'),
            'detail_sumber_limbah' => $this->faker->sentence(8),
            'jumlah_limbah_masuk' => round($jumlahMasuk, 2),
            'maksimal_penyimpanan_tanggal' => $maksimalPenyimpanan->format('Y-m-d'),
            'status_log' => $statusLog,
            'tanggal_pengangkutan' => $tanggalPengangkutan,
            'jumlah_diangkut' => round($jumlahDiangkut, 2),
            'user_id' => $user->user_id,
            'kode_limbah' => $jenisLimbah->kode_limbah,
            'perusahaan_id' => $perusahaan?->perusahaan_id,
            'unit_id' => $unitId,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa->format('Y-m-d'),
            'expiry_status' => $expiryStatus,
        ], $dokumen);
    }

    public function tersimpan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_log' => 'Tersimpan',
            'tanggal_pengangkutan' => null,
            'jumlah_diangkut' => 0,
        ]);
    }

    public function diangkut(): static
    {
        return $this->state(function (array $attributes) {
            $faker = fake();
            $tanggalMasuk = Carbon::parse($attributes['tanggal_limbah_masuk']);
            $expiryDate = Carbon::parse($attributes['tanggal_kadaluarsa']);
            $transportLimit = $expiryDate->greaterThan(now()) ? now() : $expiryDate;
            $transportDate = Carbon::instance($faker->dateTimeBetween($tanggalMasuk, $transportLimit));

            $jumlahMasuk = max(0.1, (float) ($attributes['jumlah_limbah_masuk'] ?? 1));
            $jumlahDiangkut = $faker->randomFloat(2, max(0.1, $jumlahMasuk * 0.6), $jumlahMasuk);

            return [
                'status_log' => 'Diangkut',
                'tanggal_pengangkutan' => $transportDate->format('Y-m-d'),
                'jumlah_diangkut' => round(min($jumlahMasuk, $jumlahDiangkut), 2),
            ];
        });
    }

    public function expired(): static
    {
        return $this->state(function (array $attributes) {
            $faker = fake();
            $expiryDate = now()->subDays($faker->numberBetween(1, 14));
            $daysStored = $faker->numberBetween(30, 120);
            $tanggalMasuk = (clone $expiryDate)->subDays($daysStored);

            return [
                'tanggal_limbah_masuk' => $tanggalMasuk->format('Y-m-d'),
                'maksimal_penyimpanan_tanggal' => $expiryDate->format('Y-m-d'),
                'tanggal_kadaluarsa' => $expiryDate->format('Y-m-d'),
                'expiry_status' => 'Expired',
                'status_log' => 'Kadaluarsa',
                'tanggal_pengangkutan' => null,
                'jumlah_diangkut' => 0,
            ];
        });
    }
}
