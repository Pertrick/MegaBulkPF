<?php

namespace App\Providers;

use App\Contracts\Telecom\TelecomFulfillmentGateway;
use App\Contracts\Telecom\WalletCredentialsValidator;
use App\Services\Telecom\PlanetF\PlanetFTelecomGateway;
use App\Services\Telecom\PlanetF\PlanetFWalletCredentialsValidator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TelecomFulfillmentGateway::class, PlanetFTelecomGateway::class);
        $this->app->singleton(WalletCredentialsValidator::class, PlanetFWalletCredentialsValidator::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
