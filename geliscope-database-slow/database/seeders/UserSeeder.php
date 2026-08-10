<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use App\Models\School;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first() ?? School::create([
            'name' => 'Nairobi Academy',
            'county' => 'Nairobi',
            'sub_county' => 'Westlands',
            'school_type' => 'secondary'
        ]);

        $password = Hash::make('password');

        // 3 Student Users
        $s1User = User::create([
            'first_name' => 'Brian',
            'last_name' => 'Otieno',
            'email' => 'brian.otieno@student.geliscope.com',
            'password' => $password,
            'role' => 'student',
        ]);
        $s1 = Student::create([
            'user_id' => $s1User->id,
            'admission_number' => 'ADM/2025/001',
            'school_id' => $school->id,
            'date_of_birth' => '2010-04-15',
        ]);

        $s2User = User::create([
            'first_name' => 'Faith',
            'last_name' => 'Wanjiku',
            'email' => 'faith.wanjiku@student.geliscope.com',
            'password' => $password,
            'role' => 'student',
        ]);
        $s2 = Student::create([
            'user_id' => $s2User->id,
            'admission_number' => 'ADM/2025/002',
            'school_id' => $school->id,
            'date_of_birth' => '2010-08-22',
        ]);

        $s3User = User::create([
            'first_name' => 'Kevin',
            'last_name' => 'Kipchumba',
            'email' => 'kevin.kipchumba@student.geliscope.com',
            'password' => $password,
            'role' => 'student',
        ]);
        $s3 = Student::create([
            'user_id' => $s3User->id,
            'admission_number' => 'ADM/2025/003',
            'school_id' => $school->id,
            'date_of_birth' => '2009-11-05',
        ]);

        // 2 Teacher Users
        $t1User = User::create([
            'first_name' => 'John',
            'last_name' => 'Kamau',
            'email' => 'john.kamau@teacher.geliscope.com',
            'password' => $password,
            'role' => 'teacher',
        ]);
        Teacher::create([
            'user_id' => $t1User->id,
            'employee_number' => 'TSC/10001',
            'school_id' => $school->id,
            'specialization' => 'Mathematics',
        ]);

        $t2User = User::create([
            'first_name' => 'Grace',
            'last_name' => 'Mutua',
            'email' => 'grace.mutua@teacher.geliscope.com',
            'password' => $password,
            'role' => 'teacher',
        ]);
        Teacher::create([
            'user_id' => $t2User->id,
            'employee_number' => 'TSC/10002',
            'school_id' => $school->id,
            'specialization' => 'English',
        ]);

        // 2 Parent Users
        $p1User = User::create([
            'first_name' => 'David',
            'last_name' => 'Otieno',
            'email' => 'david.otieno@parent.geliscope.com',
            'password' => $password,
            'role' => 'parent',
        ]);
        $p1 = ParentProfile::create([
            'user_id' => $p1User->id,
            'phone_number' => '+254711223344',
            'occupation' => 'Civil Engineer',
        ]);

        $p2User = User::create([
            'first_name' => 'Mercy',
            'last_name' => 'Kipchumba',
            'email' => 'mercy.kipchumba@parent.geliscope.com',
            'password' => $password,
            'role' => 'parent',
        ]);
        $p2 = ParentProfile::create([
            'user_id' => $p2User->id,
            'phone_number' => '+254722334455',
            'occupation' => 'Accountant',
        ]);

        // Link parent 1 to students 1 and 2. Link parent 2 to student 3.
        $p1->students()->attach([$s1->id, $s2->id]);
        $p2->students()->attach([$s3->id]);
    }
}
