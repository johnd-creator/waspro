<?php

namespace App\Console\Commands;

use App\Models\ApplicationSetting;
use App\Models\ApprovalLog;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CleanupPendingLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waste:cleanup-pending-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-reject pending waste logs that have exceeded the approval timeout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeoutHours = (int) ApplicationSetting::getValue('workflow.approval_timeout_hours', 72);

        $this->info("Checking for pending logs older than {$timeoutHours} hours...");

        $cutoffTime = now()->subHours($timeoutHours);

        $expiredLogs = LogPenyimpananLimbah::where('approval_status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->get();



        if ($expiredLogs->isEmpty()) {
            $this->info("No expired pending logs found.");
            return;
        }

        // Get or Create System User for auditing
        $systemUser = PenggunaSistem::firstOrCreate(
            ['email_address' => 'system@waspro.com'],
            [
                'nama_lengkap' => 'System Automation',
                'kata_sandi_hash' => Hash::make(Str::random(32)),
                'unit_id' => null,
                'aktif' => false,
            ]
        );

        $count = 0;
        foreach ($expiredLogs as $log) {
            $reason = "System Auto-Reject: Approval Timeout (> {$timeoutHours} hours)";

            try {
                DB::transaction(function () use ($log, $reason, $systemUser) {
                    $log->update([
                        'approval_status' => 'rejected',
                        'rejected_reason' => $reason,
                    ]);

                    ApprovalLog::create([
                        'log_id' => $log->log_id,
                        'approved_by' => $systemUser->user_id, // System User ID
                        'action' => 'reject',
                        'rejected_reason' => $reason,
                    ]);
                });

                $count++;
                $this->info("Rejected Log ID: {$log->log_id} - Created: {$log->created_at}");
            } catch (\Exception $e) {
                $this->error("Failed to reject Log ID: {$log->log_id}. Error: " . $e->getMessage());
            }
        }

        $this->info("Successfully auto-rejected {$count} expired pending logs.");
    }
}
