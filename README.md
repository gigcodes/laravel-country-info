# Laravel Country Info package

Provides country, locales/languages and timezone information to your Laravel project.

After running the migration and seeders, you will have 3 tables filled with the countries of the world, their locales and timezones.

Each table corresponds to a model you can use in your code. Below you find the models and their properties.

_Note: A locale is a language that is spoken in a specific country, like English UK or English US. A language is just English or French._

## Data

### Country

Properties:

- Code - 2 letter code, like `FR` or `NL`
- Title - English name, like `France`

### Locale

Properties:

- Country - country this locale belongs to (only for locales, not for languages)
- Code - format is like `nl-NL` or `en-GB`
- Title - English name, like `Afrikaans / Namibia`

### Timezone

- Country - country this timezone belongs to
- Name - English name, like `Africa/Abidjan`

## Installing the package and filling the database with all data

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

If you also want languages (locale without country) to be added. These are languages like _en_ or _nl_ so without the country attached to it.
Of course they also don't have a country relation.

`php artisan db:seed --class="Michielvaneerd\\CountryInfo\\Database\\Seeders\\LanguageSeeder"`

## Enable or disable countries, locales and timezones

Each table also has an `enabled` column that can be set to `Y` or `N`. This way you can add flags for items that should or shouldn't be displayed in your app.

This flag can be set with Artisan commands.

## Artisan commands

### Countries

List all countries:

`mve:countries-list`

List enabled or disabled countries:

`mve:countries-list --enabled=Y`

`mve:countries-list --enabled=N`

Order countries by code or title:

`mve:countries-list --order=code`

`mve:countries-list --order=title`

Enable or disable countries:

`mve:countries-edit --enable=Y --codes=FR,NL`

`mve:countries-edit --enable=N --codes=FR,NL`

### Locales

Display enabled locales for the Netherlands and France and order them by code:

`mve:locales-list --enabled=Y --order=code --countries=NL,FR`

Enable some languages:

`mve:locales-edit --enable=Y --codes=en-GB,nl-NL`

Enable all languages for a specific country:

`mve:locales-edit --enable=Y --countries=GB,NL`

### Timezones

Display enabled timezones for the Netherlands and France and order them by name:

`mve:timezones-list --enabled=Y --order=name --countries=NL,FR`

Enable some timezones:

`mve:timezones-edit --enable=Y --names="Europe/Amsterdam,Europe/Paris"`

Enable all languages for a specific country:

`mve:timezones-edit --enable=Y --countries=GB,NL`

## Using the package

```
use Michielvaneerd\CountryInfo\Models\Country;
use Michielvaneerd\CountryInfo\Models\Locale;
use Michielvaneerd\CountryInfo\Models\Timezone;

// Get one specific country
$country = Country::where('code', 'GB')->first();
echo $country->code; // GB

// Get all enabled countries
$countries = Country::where('enabled', 'Y')->get();

// Get all locales for a specific country
$locales = $country->locales;

// Get one specific locale
$locale = Locale::where('code', 'en-GB')->first();
echo $locale->code; // en-GB
echo $locale->title; // English / United Kingdom
echo $locale->country; // Country model

// Get all timezones for a specific country
$timezones = $country->timezones;

// Get one specific timezone
$timezone = Timezone::where('name', 'Europe/Amsterdam')->first();
echo $timezone->name; // Europe/Amsterdam
echo $timezone->country; // Country model

```
