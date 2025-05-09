<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRole = Role::where('slug', 'admin')->first()->id;
        $teacherRole = Role::where('slug', 'teacher')->first()->id;
        $studentRole = Role::where('slug', 'student')->first()->id;
        $parentRole = Role::where('slug', 'parent')->first()->id;

        // Create an admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole,
        ]);

        // Create teachers
        $teachers = [
            [
                'first_name' => 'Emily',
                'last_name' => 'Johnson',
                'email' => 'emily@example.com',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole,
                'subject' => 'Science',
                'avatar' => 'https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520a%2520female%2520teacher%2520with%2520glasses%252C%2520warm%2520smile%252C%2520business%2520casual%2520attire%252C%2520indoor%2520lighting%252C%2520professional%2520headshot&width=200&height=200&seq=1&orientation=squarish',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael@example.com',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole,
                'subject' => 'Mathematics',
                'avatar' => 'https://readdy.ai/api/search-image?query=professional%2520headshot%2520of%2520male%2520teacher&width=100&height=100&seq=12&orientation=squarish',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole,
                'subject' => 'English',
                'avatar' => 'https://readdy.ai/api/search-image?query=professional%2520headshot%2520of%2520female%2520teacher&width=100&height=100&seq=11&orientation=squarish',
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create($teacher);
        }

        // Create students
        $students = [
            [
                'first_name' => 'Alex',
                'last_name' => 'Smith',
                'email' => 'alex@example.com',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ],
            [
                'first_name' => 'Jessica',
                'last_name' => 'Davis',
                'email' => 'jessica@example.com',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ],
            [
                'first_name' => 'Ryan',
                'last_name' => 'Wilson',
                'email' => 'ryan@example.com',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Taylor',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'role_id' => $studentRole,
            ],
        ];

        foreach ($students as $student) {
            User::create($student);
        }

        // Create parents
        $parents = [
            [
                'first_name' => 'David',
                'last_name' => 'Smith',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'role_id' => $parentRole,
            ],
            [
                'first_name' => 'Linda',
                'last_name' => 'Davis',
                'email' => 'linda@example.com',
                'password' => Hash::make('password'),
                'role_id' => $parentRole,
            ],
        ];

        foreach ($parents as $parent) {
            User::create($parent);
        }
    }
}
