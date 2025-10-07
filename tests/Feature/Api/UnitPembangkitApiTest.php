<?php

namespace Tests\Feature\Api;

use App\Models\UnitPembangkit;

class UnitPembangkitApiTest extends ApiTestCase
{
    public function test_super_admin_can_create_unit(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $payload = [
            'nama_unit' => 'Unit Test 1',
            'alamat_unit' => 'Jl. Testing No. 1',
            'kota' => 'Jakarta',
            'kode_pos' => '12345',
            'telepon_unit' => '0211234567',
            'keterangan' => 'Unit percobaan',
            'status_aktif' => true,
        ];

        $response = $this->withToken($token)->postJson('/api/unit-pembangkit', $payload);

        $response->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama_unit', 'Unit Test 1');

        $this->assertDatabaseHas('unit_pembangkit', [
            'nama_unit' => 'Unit Test 1',
            'kota' => 'Jakarta',
        ]);
    }

    public function test_administrator_cannot_create_unit(): void
    {
        $user = $this->createUserWithRoles('Administrator');
        $token = $this->tokenFor($user);

        $response = $this->withToken($token)->postJson('/api/unit-pembangkit', [
            'nama_unit' => 'Unit Baru',
            'alamat_unit' => 'Jl. Baru No. 2',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('unit_pembangkit', ['nama_unit' => 'Unit Baru']);
    }

    public function test_administrator_can_update_own_unit(): void
    {
        $unit = UnitPembangkit::factory()->create();
        $admin = $this->createUserWithRoles('Administrator', ['unit_id' => $unit->unit_id]);
        $token = $this->tokenFor($admin);

        $response = $this->withToken($token)->patchJson('/api/unit-pembangkit/'.$unit->unit_id, [
            'keterangan' => 'Diperbarui oleh admin',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.keterangan', 'Diperbarui oleh admin');

        $this->assertDatabaseHas('unit_pembangkit', [
            'unit_id' => $unit->unit_id,
            'keterangan' => 'Diperbarui oleh admin',
        ]);
    }

    public function test_operator_cannot_update_unit(): void
    {
        $unit = UnitPembangkit::factory()->create();
        $operator = $this->createUserWithRoles('Operator', ['unit_id' => $unit->unit_id]);
        $token = $this->tokenFor($operator);

        $response = $this->withToken($token)->patchJson('/api/unit-pembangkit/'.$unit->unit_id, [
            'keterangan' => 'Seharusnya gagal',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('unit_pembangkit', [
            'unit_id' => $unit->unit_id,
            'keterangan' => 'Seharusnya gagal',
        ]);
    }

    public function test_super_admin_can_delete_unit(): void
    {
        $unit = UnitPembangkit::factory()->create();
        $super = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($super);

        $response = $this->withToken($token)->deleteJson('/api/unit-pembangkit/'.$unit->unit_id);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('unit_pembangkit', ['unit_id' => $unit->unit_id]);
    }
}
