<?php

namespace Modules\Supplier\Models;

use App\Models\Country;
use App\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Models\ScrapCategory;
use Modules\Project\Models\Project;
use Modules\Supplier\Enums\SupplierStatusEnum;

class Supplier extends Model
{
    use HasFactory;
    use BelongsToProject;

    protected $table = 'suppliers';

    protected $fillable = [

        'google_id',

        'project_id',

        'name',

        'city',

        'status',

        'email',

        'phone',

        'url',

        'social_facebook',

        'social_instagram',

        'country',

        'zip',

        'street',

        'lon',

        'lat',

        'daysoff',

        'capacity',

        'cp_title',

        'cp_name',

        'notice',

        'notice_intern',

        'updated'
    ];
    protected $casts = [

        'status' => SupplierStatusEnum::class,
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
    public function categories()
    {
        return $this->belongsToMany(
            ScrapCategory::class,
            'supplier_category_relationship',
            'supplier_id',
            'category_id'
        )->withPivot('is_main')
            ->withTimestamps();
    }


    public function countryData()
    {
        return $this->belongsTo(
            Country::class,
            'country',
            'code'
        );
    }
}
