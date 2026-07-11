<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();
        if (!$school) return;

        $c1 = ClassGroup::create([
            'school_id' => $school->id,
            'name' => 'Form 1 East',
            'academic_year' => '2025/2026',
        ]);

        $c2 = ClassGroup::create([
            'school_id' => $school->id,
            'name' => 'Form 2 West',
            'academic_year' => '2025/2026',
        ]);

        $s1 = Student::where('admission_number', 'ADM/2025/001')->first();
        $s2 = Student::where('admission_number', 'ADM/2025/002')->first();
        $s3 = Student::where('admission_number', 'ADM/2025/003')->first();

        if ($s1 && $s2) {
            $s1->update(['current_class_id' => $c1->id]);
            $s2->update(['current_class_id' => $c1->id]);
            $c1->students()->attach([$s1->id, $s2->id], ['academic_year' => '2025/2026']);
        }

        if ($s3) {
            $s3->update(['current_class_id' => $c2->id]);
            $c2->students()->attach([$s3->id], ['academic_year' => '2025/2026']);
        }

        $t1 = Teacher::where('employee_number', 'TSC/10001')->first();
        $t2 = Teacher::where('employee_number', 'TSC/10002')->first();
        $math = Subject::where('name', 'Mathematics')->first();
        $english = Subject::where('name', 'English')->first();

        if ($t1 && $math) {
            $c1->teachers()->attach($t1->id, ['subject_id' => $math->id]);
        }
        if ($t2 && $english) {
            $c2->teachers()->attach($t2->id, ['subject_id' => $english->id]);
        }
    }
}
