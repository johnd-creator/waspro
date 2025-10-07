<?php

namespace Tests\Feature\Api;

use App\Models\PerusahaanPenghasil;

class PerusahaanPenghasilApiTest extends ApiTestCase
{
    public function test_super_admin_can_create_perusahaan(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $payload = [
            'nama_perusahaan' => 'PT Data Baru',
            'jenis_perusahaan' => 'Industri',
            'npwp' => '01.234.567.8-910.000',
            'telepon' => '0219876543',
            'email' => 'kontak@ptdatabaru.co.id',
            'kota' => 'Bandung',
            'alamat_perusahaan' => 'Jl. Mawar No. 10',
            'person_in_charge' => 'Budi',
            'status_aktif' => true,
            'keterangan' => 'Perusahaan uji coba',
        ];

        $response = $this->withToken($token)->postJson('/api/perusahaan-penghasil', $payload);

        $response->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama_perusahaan', 'PT Data Baru');

        $this->assertDatabaseHas('perusahaan_penghasil', [
            'nama_perusahaan' => 'PT Data Baru',
            'kota' => 'Bandung',
        ]);
    }

    public function test_administrator_can_update_perusahaan(): void
    {
        $perusahaan = PerusahaanPenghasil::factory()->create([
            'nama_perusahaan' => 'PT Lama',
        ]);

        $admin = $this->createUserWithRoles('Administrator');
        $token = $this->tokenFor($admin);

        $response = $this->withToken($token)->patchJson('/api/perusahaan-penghasil/'.$perusahaan->perusahaan_id, [
            'nama_perusahaan' => 'PT Lama Revisi',
            'status_aktif' => false,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.nama_perusahaan', 'PT Lama Revisi')
            ->assertJsonPath('data.status_aktif', false);

        $this->assertDatabaseHas('perusahaan_penghasil', [
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'nama_perusahaan' => 'PT Lama Revisi',
            'status_aktif' => 0,
        ]);
    }

    public function test_operator_cannot_update_perusahaan(): void
    {
        $perusahaan = PerusahaanPenghasil::factory()->create([
            'nama_perusahaan' => 'PT Tidak Bisa',
        ]);

        $operator = $this->createUserWithRoles('Operator');
        $token = $this->tokenFor($operator);

        $response = $this->withToken($token)->patchJson('/api/perusahaan-penghasil/'.$perusahaan->perusahaan_id, [
            'nama_perusahaan' => 'PT Harus Gagal',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('perusahaan_penghasil', [
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'nama_perusahaan' => 'PT Harus Gagal',
        ]);
    }

    public function test_super_admin_can_delete_perusahaan(): void
    {
        $perusahaan = PerusahaanPenghasil::factory()->create([
            'nama_perusahaan' => 'PT Akan Dihapus',
        ]);

        $super = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($super);

        $response = $this->withToken($token)->deleteJson('/api/perusahaan-penghasil/'.$perusahaan->perusahaan_id);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('perusahaan_penghasil', ['perusahaan_id' => $perusahaan->perusahaan_id]);
    }
}
