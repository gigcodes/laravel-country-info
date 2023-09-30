# Laravel Country Info package

Adds data and models for all countries, languages and timezones.

## Installing the package and adding the data

First install the package:

`composer require michielvaneerd/laravel-country-info`

Then run the migration - this will create 3 tables: mvr_countries, mve_locales and mve_timezones:

`php artisan migrate`

The run the seeders.

Always first run the country seeder, because the locale and timezone seeders depend on it.

`php artisan db:seed --class="Michielvaneerd\\CountryInfo\\Database\\Seeders\\CountrySeeder"`

If you don't need locales or timezones, you can of course skip them.

`php artisan db:seed --class="Michielvaneerd\\CountryInfo\\Database\\Seeders\\LocaleSeeder"`

`php artisan db:seed --class="Michielvaneerd\\CountryInfo\\Database\\Seeders\\TimezoneSeeder"`

## Using the package

```
use Michielvaneerd\CountryInfo\Models\Country;

$country = Country::first();

$locales = $country->locales;

$timezones = $country->timezones;

```