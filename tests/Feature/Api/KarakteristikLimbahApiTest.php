<?php

namespace Tests\Feature\Api;

use App\Models\KarakteristikLimbah;

class KarakteristikLimbahApiTest extends ApiTestCase
{
    public function test_index_supports_search_and_status_filters(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        KarakteristikLimbah::factory()->create(['nama_karakteristik' => 'Korosif Cair', 'status_aktif' => true]);
        KarakteristikLimbah::factory()->create(['nama_karakteristik' => 'Reaktif Padat', 'status_aktif' => false]);

        $response = $this->withToken($token)->getJson('/api/karakteristik-limbah?search=Korosif&status_aktif=1');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.nama_karakteristik', 'Korosif Cair');
    }

    public function test_show_returns_single_karakteristik(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $karakteristik = KarakteristikLimbah::factory()->create(['nama_karakteristik' => 'Inflamable']);

        $response = $this->withToken($token)->getJson('/api/karakteristik-limbah/'.$karakteristik->karakteristik_id);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.karakteristik_id', $karakteristik->karakteristik_id)
            ->assertJsonPath('data.nama_karakteristik', 'Inflamable');
    }
}
