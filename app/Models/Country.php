<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [

        'name',
        'native_name',
        'code',
        'status'
    ];
}
