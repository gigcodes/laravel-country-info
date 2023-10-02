<?php

namespace Michielvaneerd\CountryInfo\Commands;

use Illuminate\Console\Command;
use Michielvaneerd\CountryInfo\Models\Country;
use Michielvaneerd\CountryInfo\Models\Timezone;

class TimezonesEdit extends Command
{
    private const ENABLE_YES = 'Y';
    private const ENABLE_NO = 'N';

    private const ENABLES = [self::ENABLE_YES, self::ENABLE_NO];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mve:timezones-edit
        {--countries= : Optional countries, separated by comma, required if codes if empty}
        {--names= : Timezone names separated by a comma, required if country is empty}
        {--enable= : Can be one of Y, N}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable or disable timezones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enable = $this->option('enable');
        if (!in_array($enable, self::ENABLES)) {
            $this->error('Invalid enable option.');
            return 1;
        }
        $query = Timezone::query();
        if (!empty($this->option('countries'))) {
            $countries = array_map(function($value) {
                return trim($value);
            }, explode(',', $this->option('countries')));
            $countryIds = Country::whereIn('code', $countries)->pluck('id');
            $query->whereIn('country_id', $countryIds);
        }
        if (!empty($this->option('names'))) {
            $names = array_map(function($value) {
                return trim($value);
            }, explode(',', $this->option('names')));
            $query->whereIn('name', $names);
        }
        $count = $query->count();
        if ($this->confirm('Are you sure you want to ' . ($enable === 'Y' ? 'enable' : 'disable') . ' ' . $count . ' rows?')) {
            $query->update(['enabled' => $enable]);
        }
    }
}
