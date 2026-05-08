<?php

namespace Modules\Product\Models;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'selection_type',
        'is_required',
        'has_tabs',
        'has_default',
        'sort_order',
        'active',
        'created_by',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'has_tabs' => 'boolean',
        'has_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function tabs()
    {
        return $this->hasMany(CategoryTab::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
