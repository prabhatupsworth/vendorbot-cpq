<?php

namespace Modules\Draft\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Draft\Database\Factories\DraftCategoryTranslationFactory;

class DraftCategoryTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'draft_category_id',
        'locale',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(DraftCategory::class, 'draft_category_id');
    }
}
