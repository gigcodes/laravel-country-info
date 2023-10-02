<?php

namespace Michielvaneerd\CountryInfo\Commands;

use Illuminate\Console\Command;
use Michielvaneerd\CountryInfo\Models\Country;
use Michielvaneerd\CountryInfo\Models\Timezone;

class TimezonesList extends Command
{

    private const ENABLED_Y = 'Y';
    private const ENABLED_N = 'N';

    private const ORDER_NAME = 'name';
    private const ORDER_COUNTRY = 'country';

    private const ENABLES = [self::ENABLED_Y, self::ENABLED_N];
    private const ORDERS = [self::ORDER_NAME, self::ORDER_COUNTRY];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mve:timezones-list
        {--countries= : Country codes, separated by comma (optional)}
        {--enabled= : Which items to list (optional): Y, N}
        {--order=name : Order (optional): name, country}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List timezones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Timezone::join('mve_countries', 'mve_timezones.country_id', '=', 'mve_countries.id');
        
        $enabled = $this->option('enabled');
        if (!empty($enabled)) {
            if (!in_array($enabled, self::ENABLES)) {
                $this->error('Invalid enabled option.');
                return 1;
            }
            switch ($enabled) {
                case self::ENABLED_Y:
                    $query->where('mve_timezones.enabled', 'Y');
                    break;
                case self::ENABLED_N:
                    $query->where('mve_timezones.enabled', 'N');
                    break;
            }
        }

        $order = $this->option('order');
        if (!in_array($order, self::ORDERS)) {
            $this->error('Invalid order option.');
            return 1;
        }

        if (!empty($this->option('countries'))) {
            $countries = array_map(function($value) {
                return trim($value);
            }, explode(',', $this->option('countries')));
            $countryIds = Country::whereIn('code', $countries)->pluck('id');
            $query->whereIn('country_id', $countryIds);
        }

        $timezones = $query->select('mve_timezones.id', 'mve_timezones.name', 'mve_timezones.enabled', 'mve_countries.code as country')->orderBy($order)->get()->toArray();

        $this->table(['id', 'name', 'enabled', 'country'], $timezones);
        $this->line('Number of rows: ' . count($timezones));
        $this->line('');
    }
}
