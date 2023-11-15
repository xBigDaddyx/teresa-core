<?php

namespace Teresa\CartonBoxGuard;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Teresa\CartonBoxGuard\Commands\CartonBoxGuardCommand;
use Teresa\CartonBoxGuard\Events\PolybagCreated;
use Teresa\CartonBoxGuard\Interfaces\CartonBoxValidationInterface;
use Teresa\CartonBoxGuard\Listeners\CompletedCartonBox;
use Teresa\CartonBoxGuard\Repositories\CartonBoxRepository;
use Teresa\CartonBoxGuard\Services\CartonBoxValidationService;

class CartonBoxGuardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('carton-box-guard')
            ->hasConfigFile()
            ->hasCommand(CartonBoxGuardCommand::class);
    }

    public function bootingPackage()
    {
        Event::listen(PolybagCreated::class, CompletedCartonBox::class);
    }

    public function registeringPackage()
    {
        $this->app->bind('CartonBoxRepository', CartonBoxRepository::class);
        $this->app->bind(CartonBoxValidationInterface::class, CartonBoxValidationService::class);
    }
}
