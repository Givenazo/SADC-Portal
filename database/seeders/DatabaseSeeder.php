<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Always create or fetch the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        // Only create admin user if not exists
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sadc-portal.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin1234'),
                'role_id' => $adminRole->id,
            ]
        );
        // Always update admin user's role_id to Admin role (avoid mismatch)
        if ($adminUser->role_id !== $adminRole->id) {
            $adminUser->role_id = $adminRole->id;
            $adminUser->save();
        }

        // Create a new super admin user if not exists, always assign Admin role
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@sadc-portal.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('SuperAdmin!2025'),
                'role_id' => $adminRole->id,
            ]
        );
        if ($superAdminUser->role_id !== $adminRole->id) {
            $superAdminUser->role_id = $adminRole->id;
            $superAdminUser->save();
        }

        // Create a default user for each country (avoid duplicates)
        $defaultRole = Role::where('name', '!=', 'Admin')->first();
        if ($defaultRole) {
            foreach (Country::all() as $country) {
                $email = strtolower(str_replace(' ', '', $country->name)) . '@sadc-portal.com';
                if (!User::where('email', $email)->exists()) {
                    User::factory()->create([
                        'name' => $country->name,
                        'email' => $email,
                        'password' => bcrypt('password1234'),
                        'country_id' => $country->id,
                        'role_id' => $defaultRole->id,
                    ]);
                }
            }
        }
    }
}
