<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuningCompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tuning_companies')->delete();

        DB::table('tuning_companies')->insert([
            [
                'name' => 'Maxton Design',
                'description' => 'Karosszériakitek, splitterek és aerodinamikai elemek gyártása.',
                'website_url' => 'https://maxtondesign.com',
                'image_url' => '/images/companies/maxton.png',
            ],
            [
                'name' => 'ABT Sportsline',
                'description' => 'Audi/VW/Skoda/Seat tuning, teljesítmény- és optikai fejlesztések.',
                'website_url' => 'https://www.abt-sportsline.com',
                'image_url' => '/images/companies/abt.png',
            ],
            [
                'name' => 'BRABUS',
                'description' => 'Luxus- és performance tuning, különösen Mercedes modellekhez.',
                'website_url' => 'https://www.brabus.com',
                'image_url' => '/images/companies/brabus.png',
            ],
            [
                'name' => 'AC Schnitzer',
                'description' => 'BMW/MINI tuning és prémium kiegészítők.',
                'website_url' => 'https://www.ac-schnitzer.de',
                'image_url' => '/images/companies/ac.png',
            ],
            [
                'name' => 'Mansory',
                'description' => 'High-end tuning és karosszériamódosítók.',
                'website_url' => 'https://www.mansory.com',
                'image_url' => '/images/companies/mansory.png',
            ],
            [
                'name' => 'TechArt',
                'description' => 'Porsche tuning és individualizálás.',
                'website_url' => 'https://www.techart.de',
                'image_url' => '/images/companies/techart.png',
            ],
            [
                'name' => 'Novitec',
                'description' => 'Ferrari/Lamborghini/Maserati tuning.',
                'website_url' => 'https://www.novitecgroup.com',
                'image_url' => '/images/companies/novitec.png',
            ],
            [
                'name' => 'KW suspensions',
                'description' => 'Futómű- és coilover megoldások.',
                'website_url' => 'https://www.kwsuspensions.com',
                'image_url' => '/images/companies/kw.png',
            ],
            [
                'name' => 'H&R',
                'description' => 'Rugók, futóműalkatrészek és nyomtávszélesítők.',
                'website_url' => 'https://www.h-r.com',
                'image_url' => '/images/companies/hr.png',
            ],
            [
                'name' => 'Bilstein',
                'description' => 'Gátlók és futóműkomponensek.',
                'website_url' => 'https://www.bilstein.com',
                'image_url' => '/images/companies/bilstein.png',
            ],
            [
                'name' => 'Eibach',
                'description' => 'Rugók és futómű-kiegészítők.',
                'website_url' => 'https://eibach.com',
                'image_url' => '/images/companies/eibach.png',
            ],
            [
                'name' => 'Akrapovic',
                'description' => 'Prémium kipufogórendszerek.',
                'website_url' => 'https://www.akrapovic.com',
                'image_url' => '/images/companies/akrapovic.png',
            ],
            [
                'name' => 'REMUS',
                'description' => 'Kipufogórendszerek és sportos hangzás.',
                'website_url' => 'https://remus.eu',
                'image_url' => '/images/companies/remus.png',
            ],
            [
                'name' => 'Milltek Sport',
                'description' => 'Performance kipufogórendszerek.',
                'website_url' => 'https://www.millteksport.com',
                'image_url' => '/images/companies/milltek.png',
            ],
            [
                'name' => 'Revo',
                'description' => 'ECU tuning és performance szoftver.',
                'website_url' => 'https://www.onlyrevo.com',
                'image_url' => '/images/companies/revo.png',
            ],
            [
                'name' => 'MRC Tuning',
                'description' => 'ECU tuning és dyno szolgáltatás.',
                'website_url' => 'https://www.mrctuning.com',
                'image_url' => '/images/companies/mrc.png',
            ],
            [
                'name' => 'OZ Racing',
                'description' => 'Felnik és motorsport keréktárcsák.',
                'website_url' => 'https://www.ozracing.com',
                'image_url' => '/images/companies/oz.png',
            ],
            [
                'name' => 'BBS',
                'description' => 'Felnigyártó, performance keréktárcsák.',
                'website_url' => 'https://www.bbs.com',
                'image_url' => '/images/companies/bbs.png',
            ],
            [
                'name' => 'Alpina',
                'description' => 'BMW-alapú prémium gyári tuning.',
                'website_url' => 'https://www.alpina-automobiles.com',
                'image_url' => '/images/companies/alpina.png',
            ],
            [
                'name' => 'MTM',
                'description' => 'Audi és VW performance tuning.',
                'website_url' => 'https://www.mtm-online.de',
                'image_url' => '/images/companies/mtm.png',
            ],
            [
                'name' => 'Rieger Tuning',
                'description' => 'Karosszériakitek, spoilerek és optikai tuning.',
                'website_url' => 'https://www.rieger-tuning.de',
                'image_url' => '/images/companies/rieger.png',
            ],
            [
                'name' => 'Lumma Design',
                'description' => 'Karosszériakitek és widebody csomagok.',
                'website_url' => 'https://lummadesign.com',
                'image_url' => '/images/companies/lumma.png',
            ],
            [
                'name' => 'Races',
                'description' => 'Tuning alkatrészek és teljesítménynövelő kiegészítők.',
                'website_url' => 'https://giphy.com/explore/rick-roll',
                'image_url' => '/images/companies/races.png',
            ],
            [
                'name' => 'Sparco',
                'description' => 'Versenyfelszerelések, ülések, kormányok és sportos kiegészítők.',
                'website_url' => 'https://www.sparco-official.com',
                'image_url' => '/images/companies/sparco.png',
            ],
        ]);
    }
}