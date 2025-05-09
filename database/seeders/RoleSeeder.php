<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System administrator with full access',
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Teacher with access to classes, assignments, and student data',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Student with access to assigned classes and personal data',
            ],
            [
                'name' => 'Parent',
                'slug' => 'parent',
                'description' => 'Parent with access to child data and communication',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
