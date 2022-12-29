<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LotController;
use App\Http\Interfaces\InstanceInterface;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\LotRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(LotController::class)
            ->needs(InstanceInterface::class)
            ->give(function () {
                return new LotRepository;
            });

        $this->app->when(CategoryController::class)
            ->needs(InstanceInterface::class)
            ->give(function () {
                return new CategoryRepository;
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
