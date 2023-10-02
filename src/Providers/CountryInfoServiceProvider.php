<?php

namespace Michielvaneerd\CountryInfo\Providers;

use Illuminate\Support\ServiceProvider;
use Michielvaneerd\CountryInfo\Commands\CountriesEdit;
use Michielvaneerd\CountryInfo\Commands\CountriesList;
use Michielvaneerd\CountryInfo\Commands\LocalesEdit;
use Michielvaneerd\CountryInfo\Commands\LocalesList;
use Michielvaneerd\CountryInfo\Commands\TimezonesEdit;
use Michielvaneerd\CountryInfo\Commands\TimezonesList;

class CountryInfoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        if ($this->app->runningInConsole()) {
            $this->commands([
                CountriesList::class,
                CountriesEdit::class,
                LocalesList::class,
                LocalesEdit::class,
                TimezonesList::class,
                TimezonesEdit::class
            ]);
        }
    }
}
