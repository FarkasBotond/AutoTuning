<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\ServiceCategory;
use App\Models\TuningProduct;
use Database\Seeders\CarBrandSeeder;
use Database\Seeders\CarModelSeeder;
use Database\Seeders\ServiceCategorySeeder;
use Database\Seeders\TuningProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CatalogStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_brands_table_exists_and_brand_seeder_inserts_data(): void
    {
        $this->seed(CarBrandSeeder::class);

        $this->assertTrue(Schema::hasTable('car_brands'));

        $this->assertTrue(Schema::hasColumns('car_brands', [
            'id',
            'name',
        ]));

        $this->assertGreaterThan(0, CarBrand::count());
    }

    public function test_car_models_table_exists_and_model_seeder_inserts_data(): void
    {
        $this->seed(CarBrandSeeder::class);
        $this->seed(CarModelSeeder::class);

        $this->assertTrue(Schema::hasTable('car_models'));

        $this->assertTrue(Schema::hasColumns('car_models', [
            'id',
            'brand_id',
            'model',
            'start_year',
            'end_year',
        ]));

        $this->assertGreaterThan(0, CarModel::count());

        $firstModel = CarModel::first();

        $this->assertNotNull($firstModel->brand_id);
        $this->assertDatabaseHas('car_brands', [
            'id' => $firstModel->brand_id,
        ]);
    }

    public function test_tuning_products_table_exists_and_product_seeder_inserts_data(): void
    {
        $this->seed(CarBrandSeeder::class);
        $this->seed(CarModelSeeder::class);
        $this->seed(ServiceCategorySeeder::class);
        $this->seed(TuningProductSeeder::class);

        $this->assertTrue(Schema::hasTable('tuning_products'));

        $this->assertTrue(Schema::hasColumns('tuning_products', [
            'id',
            'car_model_id',
            'service_category_id',
            'name',
            'tuning_company',
            'price',
            'old_price',
            'is_in_stock',
            'badge',
            'image',
        ]));

        $this->assertGreaterThan(0, TuningProduct::count());

        $firstProduct = TuningProduct::first();

        $this->assertDatabaseHas('car_models', [
            'id' => $firstProduct->car_model_id,
        ]);

        $this->assertDatabaseHas('service_categories', [
            'id' => $firstProduct->service_category_id,
        ]);
    }

    public function test_car_brand_model_has_many_car_models(): void
    {
        $brand = CarBrand::create([
            'name' => 'Audi',
        ]);

        $model = CarModel::create([
            'brand_id' => $brand->id,
            'model' => 'A3',
            'start_year' => 2012,
            'end_year' => 2020,
        ]);

        $this->assertTrue($brand->models->contains($model));
        $this->assertEquals('Audi', $model->brand->name);
    }

    public function test_car_model_model_belongs_to_car_brand(): void
    {
        $brand = CarBrand::create([
            'name' => 'BMW',
        ]);

        $model = CarModel::create([
            'brand_id' => $brand->id,
            'model' => 'M3',
            'start_year' => 2014,
            'end_year' => 2020,
        ]);

        $this->assertInstanceOf(CarBrand::class, $model->brand);
        $this->assertEquals($brand->id, $model->brand->id);
        $this->assertEquals('BMW', $model->brand->name);
    }

    public function test_tuning_product_model_belongs_to_car_model_and_service_category(): void
    {
        $brand = CarBrand::create([
            'name' => 'Volkswagen',
        ]);

        $model = CarModel::create([
            'brand_id' => $brand->id,
            'model' => 'Golf 7',
            'start_year' => 2012,
            'end_year' => 2020,
        ]);

        $category = ServiceCategory::create([
            'name' => 'Motor és teljesítménynövelés',
        ]);

        $product = TuningProduct::create([
            'car_model_id' => $model->id,
            'service_category_id' => $category->id,
            'name' => 'Stage 1 motorprogram',
            'tuning_company' => 'APR',
            'price' => 159000,
            'old_price' => null,
            'is_in_stock' => true,
            'badge' => 'Új',
            'image' => '/images/products/test-product.png',
        ]);

        $this->assertInstanceOf(CarModel::class, $product->carModel);
        $this->assertInstanceOf(ServiceCategory::class, $product->serviceCategory);

        $this->assertEquals('Golf 7', $product->carModel->model);
        $this->assertEquals('Volkswagen', $product->carModel->brand->name);
        $this->assertEquals('Motor és teljesítménynövelés', $product->serviceCategory->name);
    }
}