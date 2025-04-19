<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class VerifyAdminRole extends Command
{
    protected $signature = 'admin:verify {email}';
    protected $description = 'Verify and fix admin role for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $adminRole = Role::where('name', 'Admin')->first();
        
        if (!$adminRole) {
            $this->error("Admin role not found in the database!");
            return 1;
        }

        $this->info("Current user status:");
        $this->info("User ID: " . $user->id);
        $this->info("Role ID: " . $user->role_id);
        $this->info("Role Name: " . ($user->role ? $user->role->name : 'No role'));

        if ($this->confirm('Do you want to set this user as Admin?')) {
            $user->role_id = $adminRole->id;
            $user->save();
            
            $this->info("User has been set as Admin!");
            $this->info("New Role ID: " . $user->role_id);
            $this->info("New Role Name: " . $user->role->name);
        }

        return 0;
    }
} 