<?php

namespace BoukjijTarik\WooRoleManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use BoukjijTarik\WooRoleManager\Middleware\CheckPermission;
use BoukjijTarik\WooRoleManager\Middleware\CheckRole;
use BoukjijTarik\WooRoleManager\Commands\SyncRolesCommand;
use BoukjijTarik\WooRoleManager\Commands\SyncPermissionsCommand;

class WooRoleManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/woorolemanager.php', 'woorolemanager');
        
        $this->app->singleton('woorolemanager', function ($app) {
            return new WooRoleManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/woorolemanager.php' => config_path('woorolemanager.php'),
        ], 'woorolemanager-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'woorolemanager-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/woorolemanager'),
        ], 'woorolemanager-views');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'woorolemanager');

        // Register middleware
        $this->app['router']->aliasMiddleware('check.permission', CheckPermission::class);
        $this->app['router']->aliasMiddleware('check.role', CheckRole::class);

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncRolesCommand::class,
                SyncPermissionsCommand::class,
            ]);
        }

        // Register routes only if not already registered
        if (!Route::hasMacro('woorolemanagerRoutes')) {
            $this->registerRoutes();
        }

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register Gates
        $this->registerGates();
    }

    /**
     * Register package routes.
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('admin/woorolemanager')
            ->group(__DIR__.'/../routes/web.php');
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });
    }

    /**
     * Register Gates for authorization.
     */
    protected function registerGates(): void
    {
        Gate::define('manage-roles', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-permissions', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-orders', function ($user) {
            return $user->hasAnyRole(['admin', 'customer_service_agent', 'customer_service_manager']);
        });

        Gate::define('view-financial-data', function ($user) {
            return $user->hasAnyRole(['admin', 'accountant_agent', 'accountant_manager']);
        });

        Gate::define('manage-inventory', function ($user) {
            return $user->hasAnyRole(['admin', 'warehouse_manager', 'warehouse_agent']);
        });
    }
} 