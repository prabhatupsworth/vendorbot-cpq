<?php

namespace App\Models\Project;

use App\Models\PipeDrive\PipedriveField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldMapping extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'pipedrive_field_key',
        'system_field',
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

    public function pipedriveField()
    {
        return $this->belongsTo(
            PipedriveField::class,
            'pipedrive_field_key',
            'field_key'
        );
    }
}
