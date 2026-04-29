<?php

namespace App\Models\PipeDrive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipedrivePipeline extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'pipedrive_account_id',
        'pipeline_id',
        'name',
    ];

    // 🔥 Relation → belongs to account
    public function account()
    {
        return $this->belongsTo(PipedriveAccount::class, 'pipedrive_account_id');
    }

    // 🔥 Relation → has many stages
    public function stages()
    {
        return $this->hasMany(PipedriveStage::class, 'pipeline_id', 'pipeline_id');
    }
}
