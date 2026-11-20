<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSanctumApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_logs_in_inspects_profile_and_revokes_token(): void
    {
        $reg = $this->postJson('/api/v1/auth/register', [
            'name' => 'Demo User',
            'email' => 'demo@api.test',
            'password' => 'SecretPass123!',
            'password_confirmation' => 'SecretPass123!',
            'device_name' => 'phpunit',
        ]);

        $reg->assertCreated()
            ->assertJsonStructure(['token_type', 'access_token', 'user' => ['id', 'name', 'email']]);

        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'demo@api.test',
            'password' => 'SecretPass123!',
        ]);

        $login->assertOk();
        $token = $login->json('access_token');

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'demo@api.test');

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        // Ensure Sanctum token row is deleted from database
        $user = User::query()->where('email', 'demo@api.test')->firstOrFail();
        $this->assertSame(1, $user->tokens()->count()); // only the registration token remains
    }

    public function test_rejects_invalid_credentials_and_unauthenticated_requests(): void
    {
        $this->getJson('/api/v1/tasks')->assertUnauthorized();

        User::factory()->create(['email' => 'u@api.test', 'password' => 'ValidPass123!']);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'u@api.test',
            'password' => 'WrongPass!',
        ])->assertUnauthorized();
    }
}
