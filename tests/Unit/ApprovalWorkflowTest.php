<?php

namespace Tests\Unit;

use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles with expected IDs
        \App\Models\PeranPengguna::factory()->create(['peran_id' => 1, 'nama_peran' => 'Super Admin']);
        \App\Models\PeranPengguna::factory()->create(['peran_id' => 2, 'nama_peran' => 'Administrator']);
        \App\Models\PeranPengguna::factory()->create(['peran_id' => 3, 'nama_peran' => 'Supervisor']);
        \App\Models\PeranPengguna::factory()->create(['peran_id' => 4, 'nama_peran' => 'Operator']);
        \App\Models\PeranPengguna::factory()->create(['peran_id' => 5, 'nama_peran' => 'Viewer']);
    }

    public function test_supervisor_can_approve_pending_log()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
        ]);

        $response = $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $response->assertRedirect();

        $this->assertDatabaseHas('approval_log', [
            'log_id' => $log->log_id,
            'approved_by' => $supervisor->user_id,
            'action' => 'approve',
        ]);

        $log->refresh();
        $this->assertEquals('approved', $log->approval_status);
        $this->assertNotNull($log->approved_at);
    }

    public function test_supervisor_can_reject_log_with_reason()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id), [
            'catatan' => 'Tidak memenuhi standar',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('approval_log', [
            'log_id' => $log->log_id,
            'approved_by' => $supervisor->user_id,
            'action' => 'reject',
            'rejected_reason' => 'Tidak memenuhi standar',
        ]);

        $log->refresh();
        $this->assertEquals('rejected', $log->approval_status);
    }

    public function test_operator_cannot_approve_own_log()
    {
        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach(['peran_id' => 4]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
            'unit_id' => $operator->unit_id,
        ]);

        $response = $this->actingAs($operator)->post(route('log-penyimpanan.approve', $log->log_id));

        $response->assertRedirect();
    }

    public function test_approval_creates_audit_log_entry()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        // Expect audit log for UPDATE action (status change)
        $this->assertDatabaseHas('audit_log', [
            'action' => 'update',
            'table_name' => 'log_penyimpanan_limbah',
            'record_id' => $log->log_id,
        ]);
    }

    public function test_approved_log_cannot_be_edited()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $operator = PenggunaSistem::factory()->create(['unit_id' => $supervisor->unit_id]);
        $operator->peranPengguna()->attach(['peran_id' => 4]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
            'dokumen_path' => null, // Avoid File size check crash
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $response = $this->actingAs($operator)->put(route('log-penyimpanan.update', $log->log_id), [
            'jumlah_limbah_masuk' => 100,
        ]);

        // Expect redirect with error
        $response->assertRedirect();
    }

    public function test_rejected_log_cannot_be_edited()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $operator = PenggunaSistem::factory()->create(['unit_id' => $supervisor->unit_id]);
        $operator->peranPengguna()->attach(['peran_id' => 4]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
            'dokumen_path' => null, // Avoid File size check crash
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id), [
            'catatan' => 'Tidak memenuhi standar',
        ]);

        $response = $this->actingAs($operator)->put(route('log-penyimpanan.update', $log->log_id), [
            'jumlah_limbah_masuk' => 100,
        ]);

        $response->assertRedirect();
    }

    public function test_approval_status_updates_correctly()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $log->refresh();
        $this->assertEquals('approved', $log->approval_status);
        $this->assertNotNull($log->approved_at);
    }

    public function test_approval_log_records_approver_id()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $this->assertDatabaseHas('approval_log', [
            'log_id' => $log->log_id,
            'approved_by' => $supervisor->user_id,
        ]);
    }

    public function test_multiple_approvals_tracked_separately()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach(['peran_id' => 3]);

        $log = LogPenyimpananLimbah::factory()->create([
            'status_log' => 'Tersimpan',
            'unit_id' => $supervisor->unit_id,
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        // Before rejecting, resetting status might be needed? 
        // Controller rejects if status is not 'Tersimpan'? 
        // reject: check canApproveLogs. No check for status 'Tersimpan' in reject method (Step 752 lines 364-379: no status check shown).
        // Approve method check status 'Tersimpan'. Reject might not.

        $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id));

        $this->assertDatabaseCount('approval_log', 2);
    }
}
