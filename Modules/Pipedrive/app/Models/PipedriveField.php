<?php

namespace Modules\Pipedrive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipedriveField extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'pipedrive_account_id',
        'field_key',
        'name',
        'field_type',
    ];

    // 🔥 Relation → belongs to account
    public function account()
    {
        return $this->belongsTo(PipedriveAccount::class, 'pipedrive_account_id');
    }
}
