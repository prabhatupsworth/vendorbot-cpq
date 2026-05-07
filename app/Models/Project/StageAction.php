<?php

namespace App\Models\Project;

use App\Models\PipeDrive\PipedriveStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StageAction extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'project_id',

        'stage_id',

        'action_type',

        'action_config',

    ];

    protected $casts = [

        'action_config' => 'array',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function action()
    {
        return $this->belongsTo(
            Action::class,
            'action_type',
            'type_key'
        );
    }

    public function stage()
    {
        return $this->belongsTo(
            PipedriveStage::class,
            'stage_id',
            'stage_id'
        );
    }
}
