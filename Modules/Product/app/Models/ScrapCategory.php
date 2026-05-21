<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapCategory extends Model
{
    protected $fillable = [

        'scraper_category_id',

        'name',

        'description',

        'active',

    ];

    protected $casts = [

        'active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_scrap_categories'
        );
    }
}
