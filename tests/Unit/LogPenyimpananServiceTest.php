<?php

namespace Tests\Unit;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use App\Services\LogPenyimpananService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPenyimpananServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_log_sets_core_fields_correctly()
    {
        $unit = UnitPembangkit::factory()->create();
        $user = PenggunaSistem::factory()->create(['unit_id' => $unit->unit_id]);
        $jenis = JenisLimbah::factory()->create();

        $service = new LogPenyimpananService();

        $today = Carbon::today()->toDateString();

        $log = $service->createLog([
            'unit_id' => $unit->unit_id,
            'tanggal_limbah_masuk' => $today,
            'detail_sumber_limbah' => 'Sumber uji',
            'uraian_pekerjaan' => 'Pekerjaan uji',
            'jumlah_limbah_masuk' => 10.5,
            'kode_limbah' => $jenis->kode_limbah,
        ], $user->user_id, $unit->unit_id);

        $this->assertInstanceOf(LogPenyimpananLimbah::class, $log);
        $this->assertNotEmpty($log->kode_identitas);
        $this->assertEquals($unit->unit_id, $log->unit_id);
        $this->assertEquals($user->user_id, $log->user_id);
        $this->assertEquals($today, $log->tanggal_limbah_masuk->format('Y-m-d'));
        $this->assertEquals('Sumber uji', $log->detail_sumber_limbah);
        $this->assertEquals('Pekerjaan uji', $log->uraian_pekerjaan);
        $this->assertEquals(10.5, (float) $log->jumlah_limbah_masuk);
        $this->assertEquals($jenis->kode_limbah, $log->kode_limbah);
        $this->assertEquals(\App\Enums\LogStatus::Tersimpan, $log->status_log);
    }

    public function test_create_log_sets_expiry_dates_from_jenis_limbah()
    {
        $unit = UnitPembangkit::factory()->create();
        $user = PenggunaSistem::factory()->create(['unit_id' => $unit->unit_id]);

        $jenis = JenisLimbah::factory()->create([
            'waktu_penyimpanan_hari' => 30,
        ]);

        $service = new LogPenyimpananService();

        $tanggalMasuk = Carbon::create(2024, 1, 10)->toDateString();

        $log = $service->createLog([
            'unit_id' => $unit->unit_id,
            'tanggal_limbah_masuk' => $tanggalMasuk,
            'detail_sumber_limbah' => 'Sumber uji',
            'uraian_pekerjaan' => null,
            'jumlah_limbah_masuk' => 5,
            'kode_limbah' => $jenis->kode_limbah,
        ], $user->user_id, $unit->unit_id);

        $expectedExpiry = Carbon::parse($tanggalMasuk)->addDays(30)->format('Y-m-d');

        $this->assertEquals($expectedExpiry, $log->tanggal_kadaluarsa->format('Y-m-d'));
        $this->assertEquals($expectedExpiry, $log->maksimal_penyimpanan_tanggal->format('Y-m-d'));
    }

    public function test_update_log_returns_false_when_status_diangkut()
    {
        $log = LogPenyimpananLimbah::factory()->diangkut()->create();

        $service = new LogPenyimpananService();

        $result = $service->updateLog($log, [
            'jumlah_limbah_masuk' => 99,
        ]);

        $this->assertFalse($result);
        $this->assertNotEquals(99, (float) $log->fresh()->jumlah_limbah_masuk);
    }

    public function test_update_log_updates_allowed_fields_and_recalculates_expiry()
    {
        $unit = UnitPembangkit::factory()->create();
        $user = PenggunaSistem::factory()->create(['unit_id' => $unit->unit_id]);

        $jenisAwal = JenisLimbah::factory()->create([
            'waktu_penyimpanan_hari' => 10,
        ]);

        $jenisBaru = JenisLimbah::factory()->create([
            'waktu_penyimpanan_hari' => 20,
        ]);

        $perusahaanAwal = PerusahaanPenghasil::factory()->create();

        $log = LogPenyimpananLimbah::factory()->create([
            'unit_id' => $unit->unit_id,
            'user_id' => $user->user_id,
            'kode_limbah' => $jenisAwal->kode_limbah,
            'tanggal_limbah_masuk' => '2024-01-01',
            'perusahaan_id' => $perusahaanAwal->perusahaan_id,
            'status_log' => 'Tersimpan',
        ]);

        $service = new LogPenyimpananService();

        $newDate = '2024-02-01';

        $result = $service->updateLog($log, [
            'tanggal_limbah_masuk' => $newDate,
            'uraian_pekerjaan' => 'Uraian baru',
            'jumlah_limbah_masuk' => 15,
            'kode_limbah' => $jenisBaru->kode_limbah,
            'perusahaan_nama' => 'Perusahaan Uji Baru',
        ]);

        $this->assertTrue($result);

        $log->refresh();

        $this->assertEquals($newDate, $log->tanggal_limbah_masuk->format('Y-m-d'));
        $this->assertEquals('Uraian baru', $log->uraian_pekerjaan);
        $this->assertEquals(15, (float) $log->jumlah_limbah_masuk);
        $this->assertEquals($jenisBaru->kode_limbah, $log->kode_limbah);

        $this->assertNotEquals($perusahaanAwal->perusahaan_id, $log->perusahaan_id);

        $expectedExpiry = Carbon::parse($newDate)->addDays(20)->format('Y-m-d');

        $this->assertEquals($expectedExpiry, $log->tanggal_kadaluarsa->format('Y-m-d'));
        $this->assertEquals($expectedExpiry, $log->maksimal_penyimpanan_tanggal->format('Y-m-d'));
    }
}

