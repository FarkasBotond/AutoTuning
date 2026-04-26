<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {

        DB::table('service_categories')->insert([
            ['name' => 'Motor és teljesítménynövelés'],
            ['name' => 'Kipufogó és szívórendszer'],
            ['name' => 'Futómű és kormányzás'],
            ['name' => 'Fékek'],
            ['name' => 'Felnik, gumik, nyomtávszélesítők'],
            ['name' => 'Külső kiegészítők'],
            ['name' => 'Belső tér'],
            ['name' => 'Világítás és elektronika'],
            ['name' => 'Szerviz és karbantartás'],
            ['name' => 'Univerzális kiegészítők'],
        ]);
    }
}
