<?php

namespace App\Providers;

use App\Models\Inventory;
use App\Policies\InventoryPolicy;
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
        // Gate::define('update-collab', [InventoryCollaboratorPolicy::class, 'update']);
        Gate::policy(Inventory::class, InventoryPolicy::class);       
    }
}
