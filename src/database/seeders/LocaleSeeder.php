<?php

namespace Gigcodes\CountryInfo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Gigcodes\CountryInfo\Models\Country;
use Gigcodes\CountryInfo\Models\Locale;

class LocaleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $existingLocales = Locale::all()->keyBy('code');

            $languagesCodeFilePath = __DIR__ . '/../../resources/language-codes_csv.csv';
            if (!is_readable($languagesCodeFilePath)) {
                throw new \Exception('File ' . $languagesCodeFilePath . ' not found');
            }

            $localesFilePath = __DIR__ . '/../../resources/ietf-language-tags_csv.csv';
            if (!is_readable($localesFilePath)) {
                throw new \Exception('File ' . $localesFilePath . ' not found');
            }

            $fhLanguages = fopen($languagesCodeFilePath, 'r');
            if (!$fhLanguages) {
                throw new \Exception('Cannot open file ' . $languagesCodeFilePath);
            }

            $languages = [];

            $countries = Country::all()->keyBy('code');
            
            $rowCounter = 0;
            while (($row = fgetcsv($fhLanguages)) !== false) {
                $rowCounter += 1;
                if ($rowCounter === 1) {
                    continue;
                }
                if (count($row) !== 2) {
                    throw new \Exception('Row ' . $rowCounter . ': expected 2 columns, but got ' . count($row));
                }
                $code = trim($row[0]);
                $title = trim($row[1]);
                $languages[$code] = $title;
            }
            fclose($fhLanguages);

            $fhLocales = fopen($localesFilePath, 'r');
            if (!$fhLocales) {
                throw new \Exception('Cannot open file ' . $localesFilePath);
            }
            $rowCounter = 0;
            while (($row = fgetcsv($fhLocales)) !== false) {
                $rowCounter += 1;
                if ($rowCounter === 1) {
                    continue;
                }
                if (count($row) !== 2) {
                    throw new \Exception('Row ' . $rowCounter . ': expected 2 columns, but got ' . count($row));
                }
                $code = trim($row[0]);
                $countryCode = trim($row[1]);
                if (empty($countryCode) || !$countries->has($countryCode)) {
                    continue;
                }
                $country = $countries->get($countryCode);
                $localeParts = explode('-', $code);
                $languageCode = $localeParts[0];
                if (!array_key_exists($languageCode, $languages)) {
                    continue;
                }
                if ($existingLocales->has($code)) {
                    $existingLocale = $existingLocales->get($code);
                    $existingLocale->update([
                        'country_id' => $country->id,
                        'title' => $languages[$languageCode] . ' / ' . $country->title
                    ]);
                } else {
                    Locale::create([
                        'country_id' => $country->id,
                        'code' => $code,
                        'title' => $languages[$languageCode] . ' / ' . $country->title,
                        'enabled' => 'Y'
                    ]);
                }
            }
            fclose($fhLocales);
        });
    }
}
