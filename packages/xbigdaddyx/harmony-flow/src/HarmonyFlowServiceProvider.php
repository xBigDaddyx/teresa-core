<?php

namespace Xbigdaddyx\HarmonyFlow;

use Illuminate\Support\ServiceProvider;
use Xbigdaddyx\HarmonyFlow\Observers\ApprovalObserver;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Xbigdaddyx\HarmonyFlow\Commands\ApprovalCommand;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Xbigdaddyx\HarmonyFlow\Livewire\Comment;
use Xbigdaddyx\HarmonyFlow\Livewire\Status;

class HarmonyFlowServiceProvider extends PackageServiceProvider
{
    public static string $name = 'harmony-flow';

    public static string $viewNamespace = 'harmony-flow';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('xbigdaddyx/harmony-flow');
            })
            ->hasViews(static::$viewNamespace);

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }
    protected function getCommands(): array
    {
        return [
            ApprovalCommand::class,
        ];
    }
    protected function getMigrations(): array
    {
        return [
            'create_harmony_approvals_table',
        ];
    }
    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            if (class_exists(Livewire::class)) {
                Livewire::component('comment', Comment::class);
                Livewire::component('status', Status::class);
            }
        });
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        // if (app()->runningInConsole()) {
        //     foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
        //         $this->publishes([
        //             $file->getRealPath() => base_path("stubs/harmony-flow/{$file->getFilename()}"),
        //         ], 'harmony-flow-stubs');
        //     }
        // }

        // Testing
        // Testable::mixin(new TestsApproval());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'xbigdaddyx/harmony-flow';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-approvals', __DIR__ . '/../resources/dist/components/filament-approvals.js'),
            Css::make('harmony-flow-styles', __DIR__ . '/../resources/dist/harmony-flow.css'),
            Js::make('harmony-flow-scripts', __DIR__ . '/../resources/dist/harmony-flow.js'),
        ];
    }

    protected function getIcons(): array
    {
        return [];
    }


    protected function getRoutes(): array
    {
        return [];
    }


    protected function getScriptData(): array
    {
        return [];
    }
}
