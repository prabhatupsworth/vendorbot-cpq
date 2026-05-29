<?php

namespace Modules\Draft\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Draft\Database\Factories\DraftCategoryFactory;

class DraftCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sort_order',
    ];

    public function translations()
    {
        return $this->hasMany(DraftCategoryTranslation::class);
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class);
    }
}
