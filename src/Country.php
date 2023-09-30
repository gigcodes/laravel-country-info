<?php

namespace Michielvaneerd\CountryInfo;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'mve_countries';

    protected $fillable = [
        'code',
        'title'
    ];
}
