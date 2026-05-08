<?php

namespace Modules\Product\Models;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'category_id',
        'tab_id',
        'name',
        'description',
        'pdf_description',
        'price',
        'cost',
        'discount_type',
        'discount_value',
        'pipedrive_product_id',
        'is_default',
        'is_pro',
        'show_only',
        'active',
        'is_sync_backend',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_pro' => 'boolean',
        'show_only' => 'boolean',
        'active' => 'boolean',
        'is_sync_backend' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tab()
    {
        return $this->belongsTo(CategoryTab::class, 'tab_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFinalPriceAttribute()
    {
        if (!$this->discount_type) {
            return $this->price;
        }

        if ($this->discount_type === 'fixed') {
            return $this->price - $this->discount_value;
        }

        return $this->price -
            (($this->price * $this->discount_value) / 100);
    }
}
