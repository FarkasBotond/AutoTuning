<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuningCompanySeeder extends Seeder
{
    public function run(): void
    {

//TODO: legyne real adat/weblap ?????

        DB::table('tuning_companies')->insert([
    ['name' => 'Maxton Design', 'description' => 'Karosszériakitek, splitterek és aerodinamikai elemek gyártása.', 'website_url' => 'https://maxtondesign.com'],
    ['name' => 'ABT Sportsline', 'description' => 'Audi/VW/Skoda/Seat tuning, teljesítmény- és optikai fejlesztések.', 'website_url' => 'https://www.abt-sportsline.com'],
    ['name' => 'BRABUS', 'description' => 'Luxus- és performance tuning, különösen Mercedes modellekhez.', 'website_url' => 'https://www.brabus.com'],
    ['name' => 'AC Schnitzer', 'description' => 'BMW/MINI tuning és prémium kiegészítők.', 'website_url' => 'https://www.ac-schnitzer.de'],
    ['name' => 'Mansory', 'description' => 'High-end tuning és karosszériamódosítók.', 'website_url' => 'https://www.mansory.com'],
    ['name' => 'TechArt', 'description' => 'Porsche tuning és individualizálás.', 'website_url' => 'https://www.techart.de'],
    ['name' => 'Novitec', 'description' => 'Ferrari/Lamborghini/Maserati tuning.', 'website_url' => 'https://www.novitecgroup.com'],
    ['name' => 'KW suspensions', 'description' => 'Futómű- és coilover megoldások.', 'website_url' => 'https://www.kwsuspensions.com'],
    ['name' => 'H&R', 'description' => 'Rugók, futóműalkatrészek és nyomtávszélesítők.', 'website_url' => 'https://www.h-r.com'],
    ['name' => 'Bilstein', 'description' => 'Gátlók és futóműkomponensek.', 'website_url' => 'https://www.bilstein.com'],
    ['name' => 'Eibach', 'description' => 'Rugók és futómű-kiegészítők.', 'website_url' => 'https://eibach.com'],
    ['name' => 'Akrapovic', 'description' => 'Prémium kipufogórendszerek.', 'website_url' => 'https://www.akrapovic.com'],
    ['name' => 'REMUS', 'description' => 'Kipufogórendszerek és sportos hangzás.', 'website_url' => 'https://remus.eu'],
    ['name' => 'Milltek Sport', 'description' => 'Performance kipufogórendszerek.', 'website_url' => 'https://www.millteksport.com'],
    ['name' => 'Revo', 'description' => 'ECU tuning és performance szoftver.', 'website_url' => 'https://www.onlyrevo.com'],
    ['name' => 'MRC Tuning', 'description' => 'ECU tuning és dyno szolgáltatás.', 'website_url' => 'https://www.mrctuning.com'],
    ['name' => 'OZ Racing', 'description' => 'Felnik és motorsport keréktárcsák.', 'website_url' => 'https://www.ozracing.com'],
    ['name' => 'BBS', 'description' => 'Felnigyártó, performance keréktárcsák.', 'website_url' => 'https://www.bbs.com'],
    ['name' => 'Alpina', 'description' => 'BMW-alapú prémium gyári tuning.', 'website_url' => 'https://www.alpina-automobiles.com'],
    ['name' => 'MTM', 'description' => 'Audi és VW performance tuning.', 'website_url' => 'https://www.mtm-online.de'],
    ['name' => 'Rieger Tuning', 'description' => 'Karosszériakitek, spoilerek és optikai tuning.', 'website_url' => 'https://www.rieger-tuning.de'],
    ['name' => 'Kahn Design', 'description' => 'Land Rover- és prémium SUV-design tuning.', 'website_url' => 'https://www.kahndesign.com'],
    ['name' => 'Lumma Design', 'description' => 'Karosszériakitek és widebody csomagok.', 'website_url' => 'https://lummadesign.com']
]);
    }
}
