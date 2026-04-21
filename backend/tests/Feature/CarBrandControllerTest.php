<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarBrandControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a regular user
        $this->user = User::factory()->create([
            'role' => 'user'
        ]);

        // Create an admin user
        $this->adminUser = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    public function test_unauthenticated_user_cannot_list_brands(): void
    {
        $response = $this->getJson('/api/car-brands');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_list_brands(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/car-brands');

        $response->assertStatus(403);
    }

    public function test_admin_can_list_brands(): void
    {
        CarBrand::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/car-brands');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_brand(): void
    {
        $data = [
            'name' => 'BMW'
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-brands', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'BMW');

        $this->assertDatabaseHas('car_brands', ['name' => 'BMW']);
    }

    public function test_non_admin_cannot_create_brand(): void
    {
        $data = [
            'name' => 'BMW'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/car-brands', $data);

        $response->assertStatus(403);
    }

    public function test_create_brand_validation_fails_without_name(): void
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-brands', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_create_brand_validation_fails_with_duplicate_name(): void
    {
        CarBrand::create(['name' => 'BMW']);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-brands', ['name' => 'BMW']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_admin_can_show_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $brand->id)
            ->assertJsonPath('data.name', $brand->name);
    }

    public function test_admin_can_update_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->putJson("/api/car-brands/{$brand->id}", ['name' => 'Updated BMW']);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated BMW');

        $this->assertDatabaseHas('car_brands', [
            'id' => $brand->id,
            'name' => 'Updated BMW'
        ]);
    }

    public function test_non_admin_cannot_update_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/car-brands/{$brand->id}", ['name' => 'Updated BMW']);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
    }

    public function test_non_admin_cannot_delete_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(403);
    }

    public function test_delete_brand_cascades_to_models(): void
    {
        $brand = CarBrand::factory()->create();
        $models = CarBrand::find($brand->id)->models()->createMany([
            ['name' => 'Model 1', 'gen' => 'Gen1', 'mod' => 'Mod1', 'startyear' => 2020],
            ['name' => 'Model 2', 'gen' => 'Gen2', 'mod' => 'Mod2', 'startyear' => 2021],
        ]);

        $this->actingAs($this->adminUser, 'sanctum')
            ->deleteJson("/api/car-brands/{$brand->id}");

        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
        $this->assertDatabaseMissing('car_models', ['brand_id' => $brand->id]);
    }
}
