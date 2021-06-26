<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('logged-in', function ($user){
            return $user;
        });

        Gate::define('is-reseller', function ($user){
            return $user->hasAnyRole('reseller');
        });

        Gate::define('is-user-reseller', function ($user){
            return $user->hasAnyRoles(['user', 'reseller']);
        });

        Gate::define('is-user-distributor', function ($user){
            return $user->hasAnyRoles(['user', 'distributor']);
        });

        Gate::define('is-user', function ($user){
            return $user->hasAnyRole('user');
        });

        Gate::define('is-distributor', function ($user){
            return $user->hasAnyRole('distributor');
        });

        Gate::define('is-reseller-distributor', function ($user){
            return $user->hasAnyRoles(['distributor', 'reseller']);
        });

        Gate::define('is-all-roles', function ($user){
            return $user->hasAnyRoles(['distributor', 'reseller', 'user']);
        });
    }
}
