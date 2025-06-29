<?php

namespace App\Providers;

use App\Facades\PusherBeams;
use App\Service\PusherBeamService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            PusherBeamService::class,
            fn() => new PusherBeamService()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AliasLoader::getInstance()->alias('PusherBeams', PusherBeams::class);
    }
}
