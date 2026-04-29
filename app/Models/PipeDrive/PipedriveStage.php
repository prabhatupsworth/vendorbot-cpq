<?php

namespace App\Models\PipeDrive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipedriveStage extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $fillable = [
        'pipedrive_account_id',
        'stage_id',
        'pipeline_id',
        'name',
    ];

    // 🔥 Relation → belongs to account
    public function account()
    {
        return $this->belongsTo(PipedriveAccount::class, 'pipedrive_account_id');
    }

    public function pipeline()
    {
        return $this->belongsTo(PipedrivePipeline::class, 'pipeline_id', 'pipeline_id');
    }
}
