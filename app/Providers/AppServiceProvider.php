<?php

namespace App\Providers;

use App\Facades\PusherBeams;
use App\Service\PusherBeamService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
	if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        AliasLoader::getInstance()->alias('PusherBeams', PusherBeams::class);
    }
}
