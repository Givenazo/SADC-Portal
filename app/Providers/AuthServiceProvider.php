<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            $isAdmin = $user->role && $user->role->name === 'Admin';
            
            Log::info('isAdmin gate check', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role_id' => $user->role_id,
                'role_relation_exists' => isset($user->role),
                'role_name' => $user->role ? $user->role->name : null,
                'is_admin_result' => $isAdmin,
                'request_path' => request()->path(),
                'request_method' => request()->method()
            ]);
            
            return $isAdmin;
        });
    }
}
