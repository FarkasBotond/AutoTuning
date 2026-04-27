<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGuardTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function validModelPayload(int $brandId): array
    {
        return [
            'brand_id' => $brandId,
            'name' => 'A4',
            'gen' => 'B9',
            'mod' => 'Sedan',
            'startyear' => 2016,
            'endyear' => 2024,
        ];
    }

    public function test_admin_route_requires_authentication(): void
    {
        $response = $this->postJson('/api/car-brands', [
            'name' => 'Audi',
        ]);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_admin_route(): void
    {
        $user = $this->makeUser();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/car-brands', [
                'name' => 'Audi',
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('message', 'Unauthorized');
    }

    public function test_admin_user_can_access_admin_route(): void
    {
        $admin = $this->makeAdmin();

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->postJson('/api/car-brands', [
                'name' => 'Audi',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Audi');

        $this->assertDatabaseHas('car_brands', [
            'name' => 'Audi',
        ]);
    }

    public function test_unauthenticated_user_cannot_update_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->putJson("/api/car-brands/{$brand->id}", [
            'name' => 'Updated Audi',
        ]);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_update_brand(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson("/api/car-brands/{$brand->id}", [
                'name' => 'Updated Audi',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_user_can_update_brand(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create(['name' => 'Audi']);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->putJson("/api/car-brands/{$brand->id}", [
                'name' => 'Updated Audi',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Audi');

        $this->assertDatabaseHas('car_brands', [
            'id' => $brand->id,
            'name' => 'Updated Audi',
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_delete_brand(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(403);
    }

    public function test_admin_user_can_delete_brand(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
    }

    public function test_unauthenticated_user_cannot_create_model(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->postJson('/api/car-models', $this->validModelPayload($brand->id));

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_create_model(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/car-models', $this->validModelPayload($brand->id));

        $response->assertStatus(403);
    }

    public function test_admin_user_can_create_model(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->postJson('/api/car-models', $this->validModelPayload($brand->id));

        $response->assertCreated()
            ->assertJsonPath('data.name', 'A4')
            ->assertJsonPath('data.brand_id', $brand->id);
    }

    public function test_unauthenticated_user_cannot_update_model(): void
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this->putJson("/api/car-models/{$model->id}", $this->validModelPayload($brand->id));

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_update_model(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson("/api/car-models/{$model->id}", $this->validModelPayload($brand->id));

        $response->assertStatus(403);
    }

    public function test_admin_user_can_update_model(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id, 'model' => 'A3']);

        $payload = $this->validModelPayload($brand->id);
        $payload['name'] = 'A5';

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->putJson("/api/car-models/{$model->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('data.name', 'A5');

        $this->assertDatabaseHas('car_models', [
            'id' => $model->id,
            'model' => 'A5',
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_model(): void
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_delete_model(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(403);
    }

    public function test_admin_user_can_delete_model(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('car_models', ['id' => $model->id]);
    }
}
