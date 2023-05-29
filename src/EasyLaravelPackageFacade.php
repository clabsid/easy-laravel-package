<?php

namespace EasyLaravelPackage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelEasyRepository\LaravelEasyRepository
 */
class EasyLaravelPackageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'easy-laravel-package';
    }
}
