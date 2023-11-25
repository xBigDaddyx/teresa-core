<?php

namespace Xbigdaddyx\HarmonyFlow;

use Illuminate\Support\ServiceProvider;
use Xbigdaddyx\HarmonyFlow\Observers\ApprovalObserver;

class HarmonyFlowServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__ . '/../config/harmony-flow.php', 'harmony-flow');

        // Register the service the package provides.
        $this->app->singleton('harmony-flow', function ($app) {
            return new HarmonyFlow;
        });
        $this->app->singleton(ApprovalObserver::class, function (): ApprovalObserver {
            return new ApprovalObserver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['harmony-flow'];
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
            __DIR__ . '/../config/harmony-flow.php' => config_path('harmony-flow.php'),
        ], 'harmony-flow.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/xbigdaddyx'),
        ], 'harmony-flow.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/xbigdaddyx'),
        ], 'harmony-flow.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/xbigdaddyx'),
        ], 'harmony-flow.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
