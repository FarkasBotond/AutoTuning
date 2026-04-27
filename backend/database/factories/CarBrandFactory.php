<?php

namespace Database\Factories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarBrand>
 */
class CarBrandFactory extends Factory
{
    protected $model = CarBrand::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => strtoupper(fake()->unique()->lexify('brand????')),
        ];
    }
}
