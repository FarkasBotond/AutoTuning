<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_email_requires_authentication(): void
    {
        $response = $this->putJson('/api/profile/email', [
            'email' => 'new@example.com',
            'current_password' => 'secret12345',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_email_with_correct_password(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => 'secret12345',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/email', [
                'email' => 'new@example.com',
                'current_password' => 'secret12345',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.message', 'Email cím sikeresen frissítve.')
            ->assertJsonPath('data.user.email', 'new@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new@example.com',
        ]);
    }

    public function test_update_email_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'secret12345',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/email', [
                'email' => 'new@example.com',
                'current_password' => 'wrong-password',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('data.message', 'Hibás jelenlegi jelszó.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_update_email_fails_with_invalid_email_format(): void
    {
        $user = User::factory()->create([
            'password' => 'secret12345',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/email', [
                'email' => 'not-an-email',
                'current_password' => 'secret12345',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_email_fails_when_email_is_already_taken(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@example.com',
            'password' => 'secret12345',
        ]);

        User::factory()->create([
            'email' => 'taken@example.com',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/email', [
                'email' => 'taken@example.com',
                'current_password' => 'secret12345',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_update_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.message', 'Jelszó sikeresen frissítve.');

        $user->refresh();

        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_update_password_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/password', [
                'current_password' => 'wrong-password',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('data.message', 'Hibás jelenlegi jelszó.');

        $user->refresh();

        $this->assertTrue(Hash::check('oldpassword123', $user->password));
    }

    public function test_update_password_requires_confirmation_match(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword123',
                'password_confirmation' => 'different123',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_update_password_validation_fails_when_new_password_too_short(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
