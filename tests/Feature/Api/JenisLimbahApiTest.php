<?php

namespace Tests\Feature\Api;

use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;

class JenisLimbahApiTest extends ApiTestCase
{
    public function test_index_returns_filtered_paginated_results(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $karakteristikA = KarakteristikLimbah::factory()->create(['nama_karakteristik' => 'Korosif']);
        $karakteristikB = KarakteristikLimbah::factory()->create(['nama_karakteristik' => 'Nonaktif', 'status_aktif' => false]);

        JenisLimbah::factory()
            ->for($karakteristikA, 'karakteristik')
            ->create([
                'kode_limbah' => 'LMB-001',
                'nama_limbah' => 'Limbah Cair Organik',
                'status_aktif' => true,
            ]);

        JenisLimbah::factory()
            ->for($karakteristikA, 'karakteristik')
            ->create([
                'kode_limbah' => 'LMB-002',
                'nama_limbah' => 'Limbah Padat Anorganik',
                'status_aktif' => true,
            ]);

        JenisLimbah::factory()
            ->for($karakteristikB, 'karakteristik')
            ->create([
                'kode_limbah' => 'LMB-003',
                'nama_limbah' => 'Limbah B3 Khusus',
                'status_aktif' => false,
            ]);

        $response = $this->withToken($token)->getJson('/api/jenis-limbah?search=limbah&status_aktif=1&karakteristik_id='.$karakteristikA->karakteristik_id.'&per_page=1');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.pagination.current_page', 1)
            ->assertJsonPath('data.pagination.per_page', 1)
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonPath('data.items.0.kode_limbah', 'LMB-001');
    }

    public function test_show_returns_single_resource(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $karakteristik = KarakteristikLimbah::factory()->create();

        $jenisLimbah = JenisLimbah::factory()
            ->for($karakteristik, 'karakteristik')
            ->create([
                'kode_limbah' => 'LMB-099',
                'nama_limbah' => 'Limbah Uji',
            ]);

        $response = $this->withToken($token)->getJson('/api/jenis-limbah/'.$jenisLimbah->kode_limbah);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.kode_limbah', 'LMB-099')
            ->assertJsonPath('data.karakteristik.nama_karakteristik', $karakteristik->nama_karakteristik);
    }
}
