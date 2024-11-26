<?php

namespace Aliheidarian1984\QueueableStackActionsLegal;

use Aliheidarian1984\QueueableStackActionsLegal\Livewire\StackActionNotification;
use Aliheidarian1984\QueueableStackActionsLegal\Livewire\StackActionNotifications;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QueueableStackActionsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'queueable-stack-actions';

    public static string $viewNamespace = 'queueable-stack-actions';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('aliheidarian1984/filament-queueable-stack-actions-lagal');
            });

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

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
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
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/queueable-stack-actions/{$file->getFilename()}"),
                ], 'queueable-stack-actions-stubs');
            }
        }

        Livewire::component('queueable-stack-actions.stack-action-notifications', StackActionNotifications::class);
        Livewire::component('queueable-stack-actions.stack-action-notification', StackActionNotification::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'aliheidarian1984/queueable-stack-actions-legal';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('queueable-stack-actions-styles', __DIR__ . '/../resources/dist/queueable-stack-actions.css'),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_legal_stack_actions_table',
            'create_legal_stack_action_records_table',
        ];
    }
}
