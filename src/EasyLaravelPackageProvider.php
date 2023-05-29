<?php

namespace EasyLaravelPackage;

use Illuminate\Support\ServiceProvider;

use EasyLaravelPackage\Commands\MakeTrait;
use EasyLaravelPackage\Commands\MakeService;
use EasyLaravelPackage\Commands\MakeRepository;
use EasyLaravelPackage\Commands\ModelMakeCommand;

use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EasyLaravelPackageProvider extends PackageServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->registeringPackage();

        $this->package = new Package();

        $this->package->setBasePath($this->getPackageBaseDir());

        $this->configurePackage($this->package);

        if (empty($this->package->name)) {
            throw InvalidPackage::nameIsRequired();
        }

        foreach ($this->package->configFileNames as $configFileName) {
            // $this->mergeConfigFrom($this->package->basePath("/../config/laravel-command.php"), $configFileName);
            $this->mergeConfigFrom($this->package->basePath("/../config/{$configFileName}.php"), $configFileName);
        }

        $this->mergeConfigFrom(__DIR__ . "/../config/easy-laravel-package-sys.php", "easy-laravel-package");

        $this->packageRegistered();

        $this->overrideCommands();
        return $this;
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-easy-laravel-package')
            ->hasConfigFile()
            ->hasCommand(MakeTrait::class)
            ->hasCommand(MakeRepository::class)
            ->hasCommand(MakeService::class);
    }

    public function overrideCommands()
    {
        $this->app->extend('command.model.make', function () {
            return app()->make(ModelMakeCommand::class);
        });
    }
}
