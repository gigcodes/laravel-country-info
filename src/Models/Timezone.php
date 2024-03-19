<?php

namespace Gigcodes\CountryInfo\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'timezones';

    protected $fillable = [
        'country_id',
        'name',
        'enabled'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
