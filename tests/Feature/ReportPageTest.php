<?php

namespace Tests\Feature;

use App\Models\PenggunaSistem;
use App\Models\LogPenyimpananLimbah;
use App\Enums\LogStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup SQLite for tests
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::statement('PRAGMA foreign_keys=OFF;');
        }
    }

    public function test_all_report_pages_load_successfully()
    {
        // Create user
        $user = PenggunaSistem::factory()->create();

        // Create some data
        LogPenyimpananLimbah::factory()->create(['status_log' => LogStatus::Tersimpan]);
        LogPenyimpananLimbah::factory()->create(['status_log' => LogStatus::Diangkut]);

        $urls = [
            '/reports/monthly',
            '/reports/status',
            '/reports/waste-type',
            '/reports/company',
            '/reports/unit',
            '/expiry-reports',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($user)->get($url);

            if ($response->status() !== 200) {
                dump("Failed URL: $url");
                dump($response->exception?->getMessage());
            }

            $response->assertStatus(200);
        }
    }
}
