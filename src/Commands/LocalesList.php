<?php

namespace Michielvaneerd\CountryInfo\Commands;

use Illuminate\Console\Command;
use Michielvaneerd\CountryInfo\Models\Country;
use Michielvaneerd\CountryInfo\Models\Locale;

class LocalesList extends Command
{

    private const ENABLED_Y = 'Y';
    private const ENABLED_N = 'N';

    private const ORDER_CODE = 'code';
    private const ORDER_TITLE = 'title';
    private const ORDER_COUNTRY = 'country';

    private const ENABLES = [self::ENABLED_Y, self::ENABLED_N];
    private const ORDERS = [self::ORDER_CODE, self::ORDER_TITLE, self::ORDER_COUNTRY];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mve:locales-list
        {--countries= : Country codes, separated by comma (optional)}
        {--enabled= : Which items to list (optional): Y, N}
        {--order=code : Order (optional): code, title, country}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List locales, optionally only enabled or disabled ones and/or only for 1 specific country.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Locale::leftJoin('mve_countries', 'mve_locales.country_id', '=', 'mve_countries.id');

        $enabled = $this->option('enabled');
        if (!empty($enabled)) {
            if (!in_array($enabled, self::ENABLES)) {
                $this->error('Invalid enabled option.');
                return 1;
            }
            switch ($enabled) {
                case self::ENABLED_Y:
                    $query->where('mve_locales.enabled', 'Y');
                    break;
                case self::ENABLED_N:
                    $query->where('mve_locales.enabled', 'N');
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

        $locales = $query->select('mve_locales.id', 'mve_locales.code', 'mve_locales.title', 'mve_locales.enabled', 'mve_countries.code as country')->orderBy($order)->get()->toArray();

        $this->table(['id', 'code', 'title', 'enabled', 'country'], $locales);
        $this->line('Number of rows: ' . count($locales));
        $this->line('');
    }
}
