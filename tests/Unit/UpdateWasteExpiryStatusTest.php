<?php

namespace Tests\Unit;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\UnitPembangkit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateWasteExpiryStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_waste_expiry_avoids_n_plus_one_query()
    {
        // 1. Setup data: Create 10 logs with different waste types
        // Create 5 different waste types first
        $wasteTypes = JenisLimbah::factory()->count(5)->create([
            'waktu_penyimpanan_hari' => 90
        ]);

        $unit = UnitPembangkit::factory()->create();

        // Create 10 logs using these types
        foreach ($wasteTypes as $type) {
            LogPenyimpananLimbah::factory()->count(2)->create([
                'kode_limbah' => $type->kode_limbah,
                'status_log' => 'Tersimpan',
                'expiry_status' => 'Safe',
                'unit_id' => $unit->unit_id,
                'tanggal_limbah_masuk' => now()->subDays(10), // Needs calculation
            ]);
        }

        // 2. Enable query logging
        DB::enableQueryLog();

        // 3. Run command
        $this->artisan('waste:update-expiry-status', ['--force' => true])
            ->assertExitCode(0);

        // 4. Analyze queries
        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Debug output
        // dump($queries);

        // Improved assertion:
        // 1 query to fetch logs
        // 1 query to fetch related jenis_limbah (eager load)
        // 10 queries to update logs (start transaction/update/commit per log maybe? No, command does updateExpiryStatus -> update)
        // updateExpiryStatus() uses $this->update([...]).

        // If N+1 existed:
        // 1 fetch logs
        // 10 fetch jenis_limbah (lazy load per log loop)
        // 10 updates
        // Total = 21 queries.

        // With eager load:
        // 1 fetch logs
        // 1 fetch jenis_limbah (eager)
        // 10 updates
        // Total = 12 queries.

        // The difference is distinct (21 vs 12).
        // However, if we count SELECT queries only?

        $selectQueries = array_filter($queries, function ($q) {
            return stripos($q['query'], 'select') === 0;
        });
        $selectCount = count($selectQueries);

        // If N+1: 1 (logs) + 10 (lazy jenis) = 11 selects.
        // If Fixed: 1 (logs) + 1 (eager jenis) = 2 selects.
        // Maybe +1 for AppSetting fallback if not found in jenisLimbah?
        // But factory set 'waktu_penyimpanan_hari' so it returns early.

        $this->assertLessThan(5, $selectCount, "Found {$selectCount} SELECT queries. Expected < 5 (avoiding N+1).");
    }
}
