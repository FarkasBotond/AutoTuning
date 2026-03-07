<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuningCompanySeeder extends Seeder
{
    public function run(): void
    {

        DB::table('tuning_companies')->insert([
            ['name' => 'Maxton Design', 'description' => 'Body kits, splitters es aerodinamika elemek gyartasa.', 'website_url' => 'https://maxtondesign.com'],
            ['name' => 'ABT Sportsline', 'description' => 'Audi/VW/Skoda/Seat tuning, teljesitmeny es optika.', 'website_url' => 'https://www.abt-sportsline.com'],
            ['name' => 'BRABUS', 'description' => 'Luxury es performance tuning, kulonosen Mercedes.', 'website_url' => 'https://www.brabus.com'],
            ['name' => 'AC Schnitzer', 'description' => 'BMW/MINI tuning es premium kiegeszitok.', 'website_url' => 'https://www.ac-schnitzer.de'],
            ['name' => 'Mansory', 'description' => 'High-end tuning es karosszeria modositok.', 'website_url' => 'https://www.mansory.com'],
            ['name' => 'TechArt', 'description' => 'Porsche tuning es individualizalas.', 'website_url' => 'https://www.techart.de'],
            ['name' => 'Novitec', 'description' => 'Ferrari/Lamborghini/Maserati tuning.', 'website_url' => 'https://www.novitecgroup.com'],
            ['name' => 'KW suspensions', 'description' => 'Futomu es coilover megoldasok.', 'website_url' => 'https://www.kwsuspensions.com'],
            ['name' => 'H&R', 'description' => 'Rugok, futomu, nyomtavszelesitok.', 'website_url' => 'https://www.h-r.com'],
            ['name' => 'Bilstein', 'description' => 'Gatlok es futomu komponensek.', 'website_url' => 'https://www.bilstein.com'],
            ['name' => 'Eibach', 'description' => 'Rugok es futomu kiegeszitok.', 'website_url' => 'https://eibach.com'],
            ['name' => 'Akrapovic', 'description' => 'Premium kipufogo rendszerek.', 'website_url' => 'https://www.akrapovic.com'],
            ['name' => 'REMUS', 'description' => 'Kipufogo rendszerek es hang.', 'website_url' => 'https://remus.eu'],
            ['name' => 'Milltek Sport', 'description' => 'Performance kipufogo rendszerek.', 'website_url' => 'https://www.millteksport.com'],
            ['name' => 'Revo', 'description' => 'ECU tuning es performance szoftver.', 'website_url' => 'https://www.onlyrevo.com'],
            ['name' => 'MRC Tuning', 'description' => 'ECU tuning es dyno szolgaltatas.', 'website_url' => 'https://www.mrctuning.com'],
            ['name' => 'OZ Racing', 'description' => 'Felnik es motorsport kerekek.', 'website_url' => 'https://www.ozracing.com'],
            ['name' => 'BBS', 'description' => 'Felni gyarto, performance wheel.', 'website_url' => 'https://www.bbs.com'],
            ['name' => 'Alpina', 'description' => 'BMW alapokon premium gyari tuning.', 'website_url' => 'https://www.alpina-automobiles.com'],
            ['name' => 'MTM', 'description' => 'Audi es VW performance tuning.', 'website_url' => 'https://www.mtm-online.de'],
            ['name' => 'Rieger Tuning', 'description' => 'Body kit, spoilerek es optikai tuning.', 'website_url' => 'https://www.rieger-tuning.de'],
            ['name' => 'Kahn Design', 'description' => 'Land Rover es premium SUV design tuning.', 'website_url' => 'https://www.kahndesign.com'],
            ['name' => 'Lumma Design', 'description' => 'Body kit es widebody csomagok.', 'website_url' => 'https://lummadesign.com']
        ]);
    }
}
