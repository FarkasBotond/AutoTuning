<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret12345',
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret12345',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'email', 'name', 'role'],
                ],
            ])
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonPath('data.user.role', 'admin');

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => 'secret12345',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('data.message', 'Sikertelen bejelentkezés! :(');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_fails_with_unknown_email(): void
    {
        User::factory()->create([
            'email' => 'known@example.com',
            'password' => 'secret12345',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'unknown@example.com',
            'password' => 'secret12345',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('data.message', 'Sikertelen bejelentkezés! :(');
    }

    public function test_login_validation_fails_when_email_is_missing(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'secret12345',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_validation_fails_when_password_is_too_short(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_response_contains_user_name_and_role(): void
    {
        $user = User::factory()->create([
            'name' => 'Teszt Elek',
            'email' => 'teszt@example.com',
            'password' => 'secret12345',
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret12345',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.name', 'Teszt Elek')
            ->assertJsonPath('data.user.role', 'user');
    }

    public function test_logout_requires_authentication(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout_and_current_token_is_revoked(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonPath('data.message', 'Successfully logged out');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_revokes_only_current_token(): void
    {
        $user = User::factory()->create();
        $tokenA = $user->createToken('token-a')->plainTextToken;
        $tokenB = $user->createToken('token-b')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$tokenA}")
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $remainingTokenName = PersonalAccessToken::query()->first()?->name;
        $this->assertSame('token-b', $remainingTokenName);

        $this->assertNotEmpty($tokenB);
    }
}
