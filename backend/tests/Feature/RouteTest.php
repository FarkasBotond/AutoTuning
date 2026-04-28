<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_are_accessible(): void
    {
        $this->getJson('/api/car-brands')->assertOk();
        $this->getJson('/api/car-models')->assertOk();
        $this->getJson('/api/tuning-products')->assertOk();
        $this->getJson('/api/tuning-companies')->assertOk();
        $this->getJson('/api/service-categories')->assertOk();
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->postJson('/api/logout')->assertUnauthorized();
        $this->putJson('/api/profile/email')->assertUnauthorized();
        $this->putJson('/api/profile/password')->assertUnauthorized();
        $this->getJson('/api/orders/my')->assertUnauthorized();
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->postJson('/api/car-brands')->assertUnauthorized();
        $this->putJson('/api/car-brands/1')->assertUnauthorized();
        $this->deleteJson('/api/car-brands/1')->assertUnauthorized();

        $this->postJson('/api/car-models')->assertUnauthorized();
        $this->putJson('/api/car-models/1')->assertUnauthorized();
        $this->deleteJson('/api/car-models/1')->assertUnauthorized();
    }

    public function test_normal_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/car-brands')
            ->assertForbidden();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/car-models')
            ->assertForbidden();
    }
public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $brand = CarBrand::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/car-brands', [
                'name' => 'BMW',
            ])
            ->assertCreated();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/car-models', [
                'brand_id' => $brand->id,
                'name' => 'M3',
                'gen' => 'G80',
                'mod' => 'Sedan',
                'startyear' => 2020,
                'endyear' => null,
            ])
            ->assertCreated();
    }
}