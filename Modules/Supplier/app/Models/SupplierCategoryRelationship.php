<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Models\ScrapCategory;
use App\Traits\BelongsToProject;

class SupplierCategoryRelationship extends Model
{
    use HasFactory;
    use BelongsToProject;
    protected $table = 'supplier_category_relationship';

    protected $fillable = [

        'supplier_id',

        'category_id',

        'project_id',

        'is_main'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            ScrapCategory::class,
            'category_id'
        );
    }
}
