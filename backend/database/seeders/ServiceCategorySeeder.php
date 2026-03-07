<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {

        DB::table('service_categories')->insert([
            ['name' => 'ecu tuning'],
            ['name' => 'kipufogo'],
            ['name' => 'futomu'],
            ['name' => 'fek'],
            ['name' => 'kerek'],
            ['name' => 'aerodinamika'],
            ['name' => 'body kit'],
            ['name' => 'interior'],
            ['name' => 'vilagitas'],
            ['name' => 'wrap folia'],
            ['name' => 'detail'],
            ['name' => 'turbo upgrade'],
            ['name' => 'cooler'],
            ['name' => 'szivo oldal'],
            ['name' => 'motorerzes']
        ]);
    }
}
