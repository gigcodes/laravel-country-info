<?php

namespace Michielvaneerd\CountryInfo\Commands;

use Illuminate\Console\Command;
use Michielvaneerd\CountryInfo\Models\Country;

class CountriesList extends Command
{

    private const ENABLED_Y = 'Y';
    private const ENABLED_N = 'N';

    private const ORDER_CODE = 'code';
    private const ORDER_TITLE = 'title';

    private const ENABLES = [self::ENABLED_Y, self::ENABLED_N];
    private const ORDERS = [self::ORDER_CODE, self::ORDER_TITLE];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mve:countries-list
        {--enabled= : Which items to list (optional): Y, N}
        {--order=code : Ordering, can be one of code, title}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List countries, optionally only enabled or disabled ones.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Country::query();

        $enabled = $this->option('enabled');
        if (!empty($enabled)) {
            if (!in_array($enabled, self::ENABLES)) {
                $this->error('Invalid enabled option.');
                return 1;
            }
            switch ($enabled) {
                case self::ENABLED_Y:
                    $query->where('enabled', 'Y');
                    break;
                case self::ENABLED_N:
                    $query->where('enabled', 'N');
                    break;
            }
        }

        $order = $this->option('order');
        if (!in_array($order, self::ORDERS)) {
            $this->error('Invalid order option.');
            return 1;
        }
        
        $columns = ['id', 'code', 'title', 'enabled'];
        $countries = $query->select($columns)->orderBy($order)->get()->toArray();
        $this->table($columns, $countries);
        $this->line('Number of rows: ' . count($countries));
        $this->line('');
    }
}
