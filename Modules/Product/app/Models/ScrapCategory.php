<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Models\SupplierCategoryRelationship;

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


    public function supplierRelations()
    {
        return $this->hasMany(
            SupplierCategoryRelationship::class,
            'category_id'
        );
    }
}
