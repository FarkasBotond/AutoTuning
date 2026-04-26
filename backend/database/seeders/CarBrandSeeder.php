<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('car_brands')->insert([
            [
                'id' => 1,
                'name' => 'Alfa Romeo',
            ],
            [
                'id' => 2,
                'name' => 'Audi',
            ],
            [
                'id' => 3,
                'name' => 'BMW',
            ],
            [
                'id' => 4,
                'name' => 'Citroen',
            ],
            [
                'id' => 5,
                'name' => 'Cupra',
            ],
            [
                'id' => 6,
                'name' => 'Fiat',
            ],
            [
                'id' => 7,
                'name' => 'Ford',
            ],
            [
                'id' => 8,
                'name' => 'Honda',
            ],
            [
                'id' => 9,
                'name' => 'Hyundai',
            ],
            [
                'id' => 10,
                'name' => 'Kia',
            ],
            [
                'id' => 11,
                'name' => 'Mazda',
            ],
            [
                'id' => 12,
                'name' => 'Mercedes Benz',
            ],
            [
                'id' => 13,
                'name' => 'Mini',
            ],
            [
                'id' => 14,
                'name' => 'Mitsubishi',
            ],
            [
                'id' => 15,
                'name' => 'Nissan',
            ],
            [
                'id' => 16,
                'name' => 'Opel',
            ],
            [
                'id' => 17,
                'name' => 'Peugeot',
            ],
            [
                'id' => 18,
                'name' => 'Porsche',
            ],
            [
                'id' => 19,
                'name' => 'Renault',
            ],
            [
                'id' => 20,
                'name' => 'Seat',
            ],
            [
                'id' => 21,
                'name' => 'Skoda',
            ],
            [
                'id' => 22,
                'name' => 'Subaru',
            ],
            [
                'id' => 23,
                'name' => 'Suzuki',
            ],
            [
                'id' => 24,
                'name' => 'Tesla',
            ],
            [
                'id' => 25,
                'name' => 'Toyota',
            ],
            [
                'id' => 26,
                'name' => 'Volkswagen',
            ],
            [
                'id' => 27,
                'name' => 'Volvo',
            ],
        ]);
    }
}
