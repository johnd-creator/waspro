<?php

namespace Tests\Unit;

use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_approve_pending_log()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
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
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id), [
            'rejected_reason' => 'Tidak memenuhi standar',
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
        $this->assertEquals('Tidak memenuhi standar', $log->rejected_reason);
    }

    public function test_operator_cannot_approve_own_log()
    {
        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($operator)->post(route('log-penyimpanan.approve', $log->log_id));

        $response->assertForbidden();
    }

    public function test_approval_creates_audit_log_entry()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $this->assertDatabaseHas('audit_log', [
            'action' => 'create',
            'table_name' => 'log_penyimpanan_limbah',
            'record_id' => $log->log_id,
        ]);
    }

    public function test_approved_log_cannot_be_edited()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $response = $this->actingAs($operator)->put(route('log-penyimpanan.update', $log->log_id), [
            'jumlah_limbah_masuk' => 100,
        ]);

        $response->assertForbidden();
    }

    public function test_rejected_log_cannot_be_edited()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id), [
            'rejected_reason' => 'Tidak memenuhi standar',
        ]);

        $response = $this->actingAs($operator)->put(route('log-penyimpanan.update', $log->log_id), [
            'jumlah_limbah_masuk' => 100,
        ]);

        $response->assertForbidden();
    }

    public function test_approval_status_updates_correctly()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $log->refresh();
        $this->assertEquals('approved', $log->approval_status);
        $this->assertNotNull($log->approved_at);
    }

    public function test_approval_log_records_approver_id()
    {
        $supervisor = PenggunaSistem::factory()->create();
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
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
        $supervisor->peranPengguna()->attach([
            'peran_id' => 3,
        ]);

        $operator = PenggunaSistem::factory()->create();
        $operator->peranPengguna()->attach([
            'peran_id' => 4,
        ]);

        $log = LogPenyimpananLimbah::factory()->create([
            'user_id' => $operator->user_id,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($supervisor)->post(route('log-penyimpanan.approve', $log->log_id));

        $this->actingAs($supervisor)->post(route('log-penyimpanan.reject', $log->log_id));

        $this->assertDatabaseCount('approval_log', 2);
    }
}
