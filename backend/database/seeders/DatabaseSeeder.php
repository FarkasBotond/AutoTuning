<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CarBrandSeeder::class,
            CarModelSeeder::class,
            TuningCompanySeeder::class,
            ServiceCategorySeeder::class,
            CompanyCarModelSeeder::class,
            CompanyServiceSeeder::class,
        ]);
    }
}