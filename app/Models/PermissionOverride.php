<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionOverride extends Model
{
    protected $fillable = [
        'user_id',
        'permission_name',
        'is_denied'
    ];
}
