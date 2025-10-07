<?php

namespace Tests\Feature\Api\Auth;

use App\Models\PenggunaSistem;
use Tests\Feature\Api\ApiTestCase;

class LoginLogoutTest extends ApiTestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        $password = 'secret123!';
        $user = PenggunaSistem::factory()->create([
            'kata_sandi_hash' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email_address' => $user->email_address,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonPath('data.user.email', $user->email_address);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = PenggunaSistem::factory()->create();

        $response = $this->postJson('/api/login', [
            'email_address' => $user->email_address,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('errors.email_address.0', 'Kredensial yang diberikan tidak cocok dengan catatan kami.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = PenggunaSistem::factory()->inactive()->create();

        $response = $this->postJson('/api/login', [
            'email_address' => $user->email_address,
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status', 'error');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = PenggunaSistem::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Logout berhasil.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
