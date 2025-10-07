<?php

namespace Tests\Feature\Api;

use App\Models\KategoriKegiatanSumber;

class KategoriKegiatanSumberApiTest extends ApiTestCase
{
    public function test_index_supports_search_and_pagination(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        KategoriKegiatanSumber::factory()->create(['nama_kategori' => 'Industri Kimia']);
        KategoriKegiatanSumber::factory()->create(['nama_kategori' => 'Industri Tekstil']);
        KategoriKegiatanSumber::factory()->create(['nama_kategori' => 'Pertambangan']);

        $response = $this->withToken($token)->getJson('/api/kategori-kegiatan-sumber?search=Industri&per_page=1');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonPath('data.items.0.nama_kategori', 'Industri Kimia');
    }

    public function test_show_returns_single_kategori(): void
    {
        $user = $this->createUserWithRoles('Super Admin');
        $token = $this->tokenFor($user);

        $kategori = KategoriKegiatanSumber::factory()->create(['nama_kategori' => 'Laboratorium']);

        $response = $this->withToken($token)->getJson('/api/kategori-kegiatan-sumber/'.$kategori->kategori_id);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama_kategori', 'Laboratorium');
    }
}
