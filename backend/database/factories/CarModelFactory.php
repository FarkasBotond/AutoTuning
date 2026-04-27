<?php

namespace Database\Factories;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->numberBetween(1990, 2022);

        return [
            'brand_id' => CarBrand::factory(),
            'model' => strtoupper(fake()->bothify('M##')),
            'start_year' => $startYear,
            'end_year' => fake()->optional(0.7)->numberBetween($startYear, 2025),
        ];
    }
}
