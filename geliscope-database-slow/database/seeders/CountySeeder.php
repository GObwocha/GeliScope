<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\County;
use App\Models\Subcounty;
use Illuminate\Support\Facades\File;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/kenyan_county_and_subcounties.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->warn("Data file {$jsonPath} not found.");
            return;
        }
        
        $json = File::get($jsonPath);
        $countiesData = json_decode($json, true);
        
        if (!$countiesData) {
            $this->command->error("Invalid JSON data in {$jsonPath}");
            return;
        }

        foreach ($countiesData as $countyData) {
            $county = County::firstOrCreate([
                'name' => $countyData['name']
            ]);

            if (isset($countyData['subCounties']) && is_array($countyData['subCounties'])) {
                foreach ($countyData['subCounties'] as $subName) {
                    Subcounty::firstOrCreate([
                        'county_id' => $county->id,
                        'name' => $subName
                    ]);
                }
            }
        }
    }
}
