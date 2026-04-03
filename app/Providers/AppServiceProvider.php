<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Login;
use App\Listeners\RecordLoginAttendance;

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
        Paginator::useBootstrapFour();

        // Register event listeners for Login events
        Event::listen(
            Login::class,
            [\App\Listeners\RecordLoginAttendance::class, 'handle']
        );
        Event::listen(
            Login::class,
            [\App\Listeners\RecordLoginActivity::class, 'handle']
        );

        // Share urgent wholesale notifications with all views
        view()->composer('layouts.app', function ($view) {
            $urgentOrders = [];
            if (Auth::check()) {
                $urgentOrders = \App\Models\WholesaleOrder::where('status', 'pending')
                    ->where('packing_days', 1)
                    ->get();
            }
            $view->with('urgentWholesaleOrders', $urgentOrders);
        });

        // Share settings globally
        view()->composer('*', function ($view) {
            $settings = \App\Models\Setting::pluck('value', 'key');
            $view->with('app_settings', $settings);
        });

        // Define gates for different user roles
        Gate::define('manage_products', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'owner']);
        });

        Gate::define('manage_inventory', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'owner']);
        });

        Gate::define('manage_transactions', function ($user) {
            return in_array($user->role, ['admin', 'cashier', 'manager', 'owner']);
        });

        Gate::define('manage_customers', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'cashier', 'owner']);
        });

        Gate::define('manage_coupons', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'owner']);
        });

        Gate::define('manage_expenses', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'owner']);
        });

        Gate::define('view_reports', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'owner']);
        });

        Gate::define('manage_employees', function ($user) {
            return in_array($user->role, ['admin', 'owner']);
        });

        Gate::define('manage_settings', function ($user) {
            return in_array($user->role, ['admin', 'owner']);
        });
    }
}
