<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin role if it doesn't exist
        Role::firstOrCreate(['name' => 'Admin']);
    }
} 