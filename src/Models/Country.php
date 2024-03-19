<?php

namespace Gigcodes\CountryInfo\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'code',
        'title',
        'enabled'
    ];

    public function locales()
    {
        return $this->hasMany(Locale::class);
    }

    public function timezones()
    {
        return $this->hasMany(Timezone::class);
    }
}
