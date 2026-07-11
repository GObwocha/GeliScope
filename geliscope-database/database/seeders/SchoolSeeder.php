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
        School::create([
            'name' => 'Nairobi Academy',
            'county' => 'Nairobi',
            'sub_county' => 'Westlands',
            'school_type' => 'secondary',
        ]);

        School::create([
            'name' => 'Mombasa High School',
            'county' => 'Mombasa',
            'sub_county' => 'Nyali',
            'school_type' => 'secondary',
        ]);
    }
}
