<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'record_id',
        'action',
        'status',
        'message',
        'meta',
        'ip_address',
        'user_agent',
        'performed_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'performed_at' => 'datetime',
    ];

    // 🔥 Relation: who performed action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
