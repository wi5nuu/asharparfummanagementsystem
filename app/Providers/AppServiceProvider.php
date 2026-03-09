<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define gates for different user roles
        \Illuminate\Support\Facades\Gate::define('manage_products', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        \Illuminate\Support\Facades\Gate::define('manage_inventory', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        \Illuminate\Support\Facades\Gate::define('manage_transactions', function ($user) {
            return in_array($user->role, ['admin', 'cashier', 'manager']);
        });

        \Illuminate\Support\Facades\Gate::define('manage_customers', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'cashier']);
        });

        \Illuminate\Support\Facades\Gate::define('manage_coupons', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        \Illuminate\Support\Facades\Gate::define('view_reports', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        \Illuminate\Support\Facades\Gate::define('manage_employees', function ($user) {
            return $user->role === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('manage_settings', function ($user) {
            return $user->role === 'admin';
        });
    }
}
