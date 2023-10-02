<?php

namespace Michielvaneerd\CountryInfo\Commands;

use Illuminate\Console\Command;
use Michielvaneerd\CountryInfo\Models\Country;

class CountriesEdit extends Command
{
    private const ENABLE_YES = 'Y';
    private const ENABLE_NO = 'N';

    private const ENABLES = [self::ENABLE_YES, self::ENABLE_NO];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mve:countries-edit
        {--codes= : Country codes separated by a comma}
        {--enable= : Can be one of Y, N}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable or disable countries';

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
        $query = Country::query();
        
        if (!empty($this->option('codes'))) {
            $codes = array_map(function($value) {
                return trim($value);
            }, explode(',', $this->option('codes')));
            $query->whereIn('code', $codes);
        }

        $count = $query->count();
        
        if ($this->confirm('Are you sure you want to ' . ($enable === 'Y' ? 'enable' : 'disable') . ' ' . $count . ' rows?')) {
            $query->update(['enabled' => $enable]);
        }
    }
}
