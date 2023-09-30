<?php

namespace Michielvaneerd\CountryInfo\Database\Seeders;

use Illuminate\Database\Seeder;

use Michielvaneerd\CountryInfo\Country;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Country::create([
            'code' => 'NL',
            'title' => 'The Netherlands!'
        ]);
    }
}