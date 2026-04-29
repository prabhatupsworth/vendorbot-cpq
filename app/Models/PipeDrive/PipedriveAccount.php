<?php

namespace App\Models\PipeDrive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipedriveAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'u_id',
        'account_name',
        'api_key',
        'base_url',
        'is_verified',
        'sync_stages',
        'sync_fields',
    ];

    public function fields()
    {
        return $this->hasMany(PipedriveField::class, 'pipedrive_account_id');
    }

    public function stages()
    {
        return $this->hasMany(PipedriveStage::class, 'pipedrive_account_id');
    }
}
