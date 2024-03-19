<?php

namespace Gigcodes\CountryInfo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Gigcodes\CountryInfo\Models\Country;
use Gigcodes\CountryInfo\Models\Locale;

class LanguageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $existingLocales = Locale::all()->keyBy('code');
            $langDone = [];
            foreach ($existingLocales as $code => $locale) {
                $parts = explode('-', $code);
                $language = $parts[0];
                if (!$existingLocales->has($language) && !array_key_exists($language, $langDone)) {
                    $titleParts = explode('/', $locale->title);
                    Locale::create([
                        'code' => $language,
                        'enabled' => 'N',
                        'country_id' => null,
                        'title' => trim($titleParts[0])
                    ]);
                    $langDone[$language] = true;
                }
            }
            
        });
    }
}
