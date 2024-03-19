<?php

namespace Gigcodes\CountryInfo\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $table = 'locales';

    protected $fillable = [
        'country_id',
        'code',
        'title',
        'enabled'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
