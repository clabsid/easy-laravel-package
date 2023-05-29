<?php

namespace EasyLaravelPackage\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use EasyLaravelPackage\AssistCommand;
use EasyLaravelPackage\CreateFile;

class MakeTrait extends Command
{
    use AssistCommand;

    public $signature = 'make:trait
        {name : The name of the trait }
        {--other : If not put, it will create an eloquent repository}?';

    public $description = 'Create a new trait class';

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $name = str_replace(config("easy-laravel-package.trait_suffix"), "", $this->argument("name"));
        $name = Str::studly($name);

        $other = $this->option("other");

        $className = Str::studly($name);
        $arr = explode("/", $className);
        $className = end($arr);
        $this->checkIfRequiredDirectoriesExist($className);
        $this->createTrait($className, ! $other);
    }

    private function getTraitPath($className, $isDefault)
    {
        $path = $isDefault
            ? "/" . $className . "/$className" . config("easy-laravel-package.trait_suffix").".php"
            : "/Other/$className" .  config("easy-laravel-package.trait_suffix") . ".php";

        return $this->appPath() . "/" .
            config("easy-laravel-package.trait_directory") . $path;
    }

    public function createTrait(string $className, $isDefault = true)
    {
        $traitNamespace = config("easy-laravel-package.trait_namespace") . "\\" . $className;
        $traitName = $className . config("easy-laravel-package.trait_suffix");
        $stubProperties = [
            "{namespace}" => $traitNamespace,
            "{traitName}" => $traitName,
        ];
        $stubName = "trait.stub";
        $traitPath = $this->getTraitPath($className, $isDefault);
        new CreateFile(
            $stubProperties,
            $traitPath,
            __DIR__ . "/stubs/$stubName"
        );
        $this->line("<info>Created $className trait:</info> " . $traitName);

        return $traitNamespace . "\\" . $className;
    }

    /**
     * Check to make sure if all required directories are available
     *
     * @return void
     */
    private function checkIfRequiredDirectoriesExist(string $className)
    {
        $this->ensureDirectoryExists(config("easy-laravel-package.trait_directory"));
        $this->ensureDirectoryExists(config("easy-laravel-package.trait_directory") . "/". $className);
        $this->ensureDirectoryExists(config("easy-laravel-package.trait_directory") . "/" . $className);
    }
}
