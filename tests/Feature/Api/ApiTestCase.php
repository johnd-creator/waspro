<?php

namespace Tests\Feature\Api;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedDefaultRoles();
    }

    protected function seedDefaultRoles(): void
    {
        $roles = [
            'Super Admin' => 'Memiliki akses penuh terhadap sistem.',
            'Administrator' => 'Mengelola master data dan operasional unit.',
            'Operator' => 'Mengelola data operasional harian.',
            'Viewer' => 'Hanya dapat melihat data.',
        ];

        foreach ($roles as $name => $description) {
            PeranPengguna::create([
                'nama_peran' => $name,
                'deskripsi' => $description,
                'is_active' => true,
            ]);
        }
    }

    protected function createUserWithRoles(array|string $roles, array $attributes = []): PenggunaSistem
    {
        $roles = is_array($roles) ? $roles : [$roles];

        $user = PenggunaSistem::factory()->create($attributes);

        $roleIds = PeranPengguna::whereIn('nama_peran', $roles)->pluck('peran_id');

        foreach ($roleIds as $roleId) {
            $user->peranPengguna()->attach($roleId);
        }

        return $user;
    }

    protected function tokenFor(PenggunaSistem $user): string
    {
        return $user->createToken('api-test-token')->plainTextToken;
    }
}
