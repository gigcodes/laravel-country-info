<?php

namespace Gigcodes\CountryInfo\Providers;

use Illuminate\Support\ServiceProvider;
use Gigcodes\CountryInfo\Commands\CountriesEdit;
use Gigcodes\CountryInfo\Commands\CountriesList;
use Gigcodes\CountryInfo\Commands\LocalesEdit;
use Gigcodes\CountryInfo\Commands\LocalesList;
use Gigcodes\CountryInfo\Commands\TimezonesEdit;
use Gigcodes\CountryInfo\Commands\TimezonesList;

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
