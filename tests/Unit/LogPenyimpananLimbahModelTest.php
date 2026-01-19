<?php

namespace Tests\Unit;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\UnitPembangkit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPenyimpananLimbahModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_expiry_date_uses_jenis_limbah_days()
    {
        $unit = UnitPembangkit::factory()->create();

        $jenis = JenisLimbah::factory()->create([
            'waktu_penyimpanan_hari' => 30,
        ]);

        $tanggalMasuk = '2024-01-10';

        $log = LogPenyimpananLimbah::factory()->create([
            'unit_id' => $unit->unit_id,
            'kode_limbah' => $jenis->kode_limbah,
            'tanggal_limbah_masuk' => $tanggalMasuk,
        ]);

        $expiry = $log->calculateExpiryDate();

        $this->assertNotNull($expiry);
        $this->assertEquals(
            Carbon::parse($tanggalMasuk)->addDays(30)->format('Y-m-d'),
            $expiry->format('Y-m-d')
        );
    }

    public function test_get_days_until_expiry_returns_integer()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1));

        $unit = UnitPembangkit::factory()->create();

        $log = LogPenyimpananLimbah::factory()->create([
            'unit_id' => $unit->unit_id,
            'tanggal_kadaluarsa' => Carbon::now()->copy()->addDays(5)->format('Y-m-d'),
        ]);

        $daysUntilExpiry = $log->getDaysUntilExpiry();

        $this->assertIsInt($daysUntilExpiry);
        $this->assertEquals(5, $daysUntilExpiry);

        Carbon::setTestNow();
    }
}

