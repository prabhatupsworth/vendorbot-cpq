<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Project\Models\Project;
use App\Traits\BelongsToProject;

class Product extends Model
{
    use SoftDeletes;
    use BelongsToProject;

    protected $fillable = [
        'project_id',
        'crm_product_id',
        'title',
        'sub_title',
        'product_code',
        'cost',
        'price',
        'currency_code',
        'description',
        'proposal_desc',
        'is_best_seller',
        'is_sync_backend',
        'active',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'price' => 'decimal:2',

        'is_best_seller' => 'boolean',
        'is_sync_backend' => 'boolean',
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scrapCategories()
    {
        return $this->belongsToMany(
            ScrapCategory::class,
            'product_scrap_categories',
            'product_id',
            'scrap_category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', 1);
    }

    public function scopeSynced($query)
    {
        return $query->where('is_sync_backend', 1);
    }
}
