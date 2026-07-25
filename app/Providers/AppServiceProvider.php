<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        $this->registerGates();
    }

    /**
     * Coarse ability gates used across the app. Fine-grained,
     * per-record rules live in model Policies (added per module).
     */
    protected function registerGates(): void
    {
        // Admins pass every gate/policy check automatically.
        Gate::before(fn (User $user) => $user->isAdmin() ? true : null);

        Gate::define('access-admin', fn (User $user) => $user->isAdmin());
        Gate::define('manage-academics', fn (User $user) => $user->isTeacher());
        Gate::define('view-reports', fn (User $user) => $user->isTeacher());
    }
}
