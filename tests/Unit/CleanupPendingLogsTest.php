<?php

namespace Tests\Unit;

use App\Models\ApprovalLog;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CleanupPendingLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_pending_logs_transaction_success()
    {
        // 1. Create a pending log older than 72 hours
        // We need to ensure we have a valid unit_id and other required fields via factory
        $log = LogPenyimpananLimbah::factory()->create([
            'approval_status' => 'pending',
        ]);
        // Manually set created_at because it is guarding/not-fillable by default
        $log->created_at = now()->subHours(80);
        $log->save();

        // 2. Run command
        $this->artisan('waste:cleanup-pending-logs')
            ->assertExitCode(0);

        // 3. Verify log rejected
        $this->assertDatabaseHas('log_penyimpanan_limbah', [
            'log_id' => $log->log_id,
            'approval_status' => 'rejected',
        ]);

        // 4. Verify approval log created
        // Fetch the system user to verify ID
        $systemUser = PenggunaSistem::where('email_address', 'system@waspro.com')->first();
        $this->assertNotNull($systemUser, 'System user should be created');

        $this->assertDatabaseHas('approval_log', [
            'log_id' => $log->log_id,
            'action' => 'reject',
            'approved_by' => $systemUser->user_id,
            // 'rejected_reason' should contain "System Auto-Reject"
        ]);

        // Detailed check on reason
        $updatedLog = $log->fresh();
        $this->assertStringContainsString('System Auto-Reject', $updatedLog->rejected_reason);
    }

    public function test_cleanup_pending_logs_rollback_on_failure()
    {
        // 1. Create a pending log
        $log = LogPenyimpananLimbah::factory()->create([
            'approval_status' => 'pending',
        ]);
        // Manually set created_at
        $log->created_at = now()->subHours(80);
        $log->save();

        // 2. Mock ApprovalLog to throw exception on creation
        // Prevent event propagation after this test
        ApprovalLog::creating(function ($model) {
            throw new \Exception('Simulated Failure for Rollback Test');
        });

        // 3. Run command
        // It should exit 0 because we catch the exception for individual items
        $this->artisan('waste:cleanup-pending-logs')
            ->assertExitCode(0);

        // 4. Verify LogPenyimpananLimbah is NOT rejected (rolled back)
        $log->refresh();
        $this->assertEquals('pending', $log->approval_status);

        // Verify no approval log
        $this->assertDatabaseMissing('approval_log', [
            'log_id' => $log->log_id,
        ]);
    }
}
