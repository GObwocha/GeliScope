<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamRoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('exam_rounds')->insert([
            [
                'name' => 'Mid Term 1', 
                'term' => 1, 
                'academic_year' => '2025',
                'start_date' => '2025-02-15',
                'end_date' => '2025-02-28',
            ],
            [
                'name' => 'End Term 1', 
                'term' => 1, 
                'academic_year' => '2025',
                'start_date' => '2025-04-01',
                'end_date' => '2025-04-15',
            ],
            [
                'name' => 'Mid Term 2', 
                'term' => 2, 
                'academic_year' => '2025',
                'start_date' => '2025-06-15',
                'end_date' => '2025-06-30',
            ],
        ]);
    }
}
