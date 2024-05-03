<?php

namespace Gigcodes\CountryInfo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Gigcodes\CountryInfo\Models\Country;
use Gigcodes\CountryInfo\Models\Timezone;

class TimezoneSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $filePath = __DIR__ . '/../../resources/timezones.csv';
            if (!is_readable($filePath)) {
                throw new \Exception('File ' . $filePath . ' not found');
            }
            $fh = fopen($filePath, 'r');
            if (!$fh) {
                throw new \Exception('Cannot open file ' . $filePath);
            }
            $existingTimezones = Timezone::all()->keyBy('name');
            $countries = Country::all()->keyBy('code');
            $rowCounter = 0;
            while (($row = fgetcsv($fh)) !== false) {
                $rowCounter += 1;
                if (count($row) !== 2) {
                    throw new \Exception('Row ' . $rowCounter . ': expected 2 columns, but got ' . count($row));
                }
                $name = trim($row[0]);
                $countryCode = trim($row[1]);
                if (!$countries->has($countryCode)) {
                    continue;
                }
                $country = $countries->get($countryCode);
                if ($existingTimezones->has($name)) {
                    $existingTimezone = $existingTimezones->get($name);
                    if ($existingTimezone->country_id !== $country->id) {
                        $existingTimezone->update([
                            'country_id' => $country->id
                        ]);
                    }
                } else {
                    $newTimezone = Timezone::create([
                        'name' => $name,
                        'country_id' => $country->id,
                        'enabled' => 'Y'
                    ]);
                    $existingTimezones->put($name, $newTimezone);
                }
            }
            fclose($fh);
        });
    }
}
