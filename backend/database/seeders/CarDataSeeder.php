<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CarDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $r = Http::get('http://jsonserver:3000/data');
        if (! $r->successful()){
            $this->command->error("Error!");
        }
        $carsData = $r->json();

        foreach($carsData['data'] as $car){
            CarBrand::query()->firstOrCreate([
                'name'=>$car['brand'],
            ]);
            CarModel::query()->firstOrCreate([
                'brand_id'=>CarBrand::query()->where('name', $car['brand'])->value('id'),
                'name'=>$car['model'],
                'gen'=>$car['gen'],
                'mod'=>$car['mod'],
                'startyear'=>$car['start_year'],
                'endyear'=>$car['end_year']=="" ? null : $car['end_year'],
            ]);
        }
    }
}
