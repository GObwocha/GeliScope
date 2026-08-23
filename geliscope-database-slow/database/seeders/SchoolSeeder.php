<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First get a subcounty for Nairobi and Mombasa (assuming CountySeeder ran first)
        $nairobiSub = \App\Models\Subcounty::where('name', 'Westlands')->first();
        $mombasaSub = \App\Models\Subcounty::where('name', 'Nyali')->first();

        School::create([
            'name' => 'Nairobi Academy',
            'subcounty_id' => $nairobiSub ? $nairobiSub->id : 1,
            'school_type' => 'secondary',
        ]);

        School::create([
            'name' => 'Mombasa High School',
            'subcounty_id' => $mombasaSub ? $mombasaSub->id : 2,
            'school_type' => 'secondary',
        ]);
    }
}
