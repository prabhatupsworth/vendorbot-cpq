<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTab extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'is_default',
        'sort_order',
        'active',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'tab_id');
    }
}
