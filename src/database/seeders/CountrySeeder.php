<?php

namespace Michielvaneerd\CountryInfo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Michielvaneerd\CountryInfo\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $countriesFilePath = __DIR__ . '/../../resources/countries.csv';
            if (!is_readable($countriesFilePath)) {
                throw new \Exception('File ' . $countriesFilePath . ' not found');
            }
            $fh = fopen($countriesFilePath, 'r');
            if (!$fh) {
                throw new \Exception('Cannot open file ' . $countriesFilePath);
            }
            $existingCountries = Country::all()->keyBy('code');
            $rowCounter = 0;
            while (($row = fgetcsv($fh)) !== false) {
                $rowCounter += 1;
                if ($rowCounter === 1) {
                    continue;
                }
                if (count($row) !== 2) {
                    throw new \Exception('Row ' . $rowCounter . ': expected 2 columns, but got ' . count($row));
                }
                $title = trim($row[0]);
                $code = trim($row[1]);
                if ($existingCountries->has($code)) {
                    $country = $existingCountries->get($code);
                    if ($title !== $country->title) {
                        $country->update(['title' => $title]);
                    }
                } else {
                    Country::create(['code' => $code, 'title' => $title, 'enabled' => 'N']);
                }
            }
            fclose($fh);
        });
    }
}
