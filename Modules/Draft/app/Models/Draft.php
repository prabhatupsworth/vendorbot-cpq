<?php

namespace Modules\Draft\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\Models\Project;

use App\Traits\BelongsToProject;

class Draft extends Model
{
    use HasFactory, BelongsToProject;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'draft_category_id',
        'subject',
        'content'
    ];

    public function category()
    {
        return $this->belongsTo(DraftCategory::class, 'draft_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
