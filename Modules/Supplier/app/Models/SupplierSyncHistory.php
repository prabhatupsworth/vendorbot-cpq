<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\Models\Project;
use App\Traits\BelongsToProject;

class SupplierSyncHistory extends Model
{
    use HasFactory;
    use BelongsToProject;
    protected $fillable = [
        'project_id',
        'sync_period',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}
