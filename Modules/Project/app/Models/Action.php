<?php

namespace Modules\Project\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'action_name',
        'type_key',
    ];
}
