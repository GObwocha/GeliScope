<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'category' => 'Sciences'],
            ['name' => 'English', 'category' => 'Languages'],
            ['name' => 'Physics', 'category' => 'Sciences'],
            ['name' => 'Chemistry', 'category' => 'Sciences'],
            ['name' => 'Biology', 'category' => 'Sciences'],
            ['name' => 'History', 'category' => 'Humanities'],
            ['name' => 'Geography', 'category' => 'Humanities'],
            ['name' => 'Kiswahili', 'category' => 'Languages'],
        ];

        foreach ($subjects as $s) {
            Subject::create($s);
        }
    }
}
