<?php

namespace Xbigdaddyx\ApprovalSystem;

use Illuminate\Support\ServiceProvider;

class ApprovalSystemServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xbigdaddyx');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'xbigdaddyx');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/approval-system.php', 'approval-system');

        // Register the service the package provides.
        $this->app->singleton('approval-system', function ($app) {
            return new ApprovalSystem;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['approval-system'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/approval-system.php' => config_path('approval-system.php'),
        ], 'approval-system.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/xbigdaddyx'),
        ], 'approval-system.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/xbigdaddyx'),
        ], 'approval-system.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/xbigdaddyx'),
        ], 'approval-system.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
