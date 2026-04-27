<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarModelControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $adminUser;
    private $brand;

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

        // Create a brand for testing
        $this->brand = CarBrand::factory()->create();
    }

    public function test_unauthenticated_user_can_list_models(): void
    {
        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200);
    }

    public function test_non_admin_user_can_list_models(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/car-models');

        $response->assertStatus(200);
    }

    public function test_admin_can_list_models(): void
    {
        CarModel::factory()->count(3)->create(['brand_id' => $this->brand->id]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_list_models_includes_brand_relationship(): void
    {
        CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'brand_id',
                        'name',
                        'gen',
                        'mod',
                        'startyear',
                        'endyear',
                        'brand'
                    ]
                ]
            ]);
    }

    public function test_admin_can_create_model(): void
    {
        $data = [
            'brand_id' => $this->brand->id,
            'name' => '320i',
            'gen' => 'F30',
            'mod' => 'Sedan',
            'startyear' => 2012,
            'endyear' => 2019
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-models', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', '320i')
            ->assertJsonPath('data.brand_id', $this->brand->id);

        $this->assertDatabaseHas('car_models', ['model' => '320i']);
    }

    public function test_non_admin_cannot_create_model(): void
    {
        $data = [
            'brand_id' => $this->brand->id,
            'name' => '320i',
            'gen' => 'F30',
            'mod' => 'Sedan',
            'startyear' => 2012
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/car-models', $data);

        $response->assertStatus(403);
    }

    public function test_create_model_validation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-models', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['brand_id', 'name', 'gen', 'mod', 'startyear']);
    }

    public function test_create_model_validation_fails_with_invalid_brand(): void
    {
        $data = [
            'brand_id' => 9999,
            'name' => '320i',
            'gen' => 'F30',
            'mod' => 'Sedan',
            'startyear' => 2012
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-models', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('brand_id');
    }

    public function test_admin_can_show_model(): void
    {
        $model = CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $model->id)
            ->assertJsonPath('data.name', $model->model)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'brand_id',
                    'name',
                    'gen',
                    'mod',
                    'startyear',
                    'endyear',
                    'brand'
                ]
            ]);
    }

    public function test_admin_can_update_model(): void
    {
        $model = CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $data = [
            'brand_id' => $this->brand->id,
            'name' => 'Updated Model',
            'gen' => 'F31',
            'mod' => 'Touring',
            'startyear' => 2013,
            'endyear' => 2020
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->putJson("/api/car-models/{$model->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Model');

        $this->assertDatabaseHas('car_models', [
            'id' => $model->id,
            'model' => 'Updated Model'
        ]);
    }

    public function test_non_admin_cannot_update_model(): void
    {
        $model = CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $data = [
            'brand_id' => $this->brand->id,
            'name' => 'Updated Model',
            'gen' => 'F31',
            'mod' => 'Touring',
            'startyear' => 2013
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/car-models/{$model->id}", $data);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_model(): void
    {
        $model = CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('car_models', ['id' => $model->id]);
    }

    public function test_non_admin_cannot_delete_model(): void
    {
        $model = CarModel::factory()->create(['brand_id' => $this->brand->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(403);
    }

    public function test_model_validation_requires_valid_year_range(): void
    {
        $data = [
            'brand_id' => $this->brand->id,
            'name' => '320i',
            'gen' => 'F30',
            'mod' => 'Sedan',
            'startyear' => 2020,
            'endyear' => 2015
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/car-models', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('endyear');
    }
}
