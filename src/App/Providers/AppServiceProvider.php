<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\ServiceProvider;
use Teresa\CartonBoxGuard\Interfaces\CartonBoxValidationInterface;
use Teresa\CartonBoxGuard\Repositories\CartonBoxRepository;
use Teresa\CartonBoxGuard\Services\CartonBoxValidationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Factories\Factory::guessFactoryNamesUsing(function (string $modelName) {
            return '\Database\Factories\\' . class_basename($modelName) . 'Factory';
        });

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch->simple();

            // ->modalHeading('Available Panels')
            // ->labels([
            //     'admin' => 'Administration',
            //     'accuracy' => __('Accuracy'),
            // ])
            // ->icons([
            //     'admin' => 'heroicon-o-key',
            //     'accuracy' => 'heroicon-o-check-badge',
            // ], $asImage = false)
            // ->iconSize(16)
            // ->canSwitchPanels(fn (): bool => auth()->user()?->hasRole('super-admin'));
        });
    }
}
