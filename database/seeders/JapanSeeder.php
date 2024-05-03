<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class JapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $japanStates = [
            "KYU" => 'Kushu',
            "SHI" => 'Shikoku',
            "KAN" => 'Kantou',
            "HDO" => 'Hokkaid',
            "KAI" => 'Kansai',
            "CHU" => 'Chugoku',
        ];

        $countries = [
            ['code' => 'jp', 'name' => 'Japan', 'states' => json_encode($japanStates)],
        ];
        Country::insert($countries);

    }
}
