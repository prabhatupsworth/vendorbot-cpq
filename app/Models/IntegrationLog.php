<?php

namespace App\Models;

use App\Models\PipeDrive\PipedriveAccount;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    protected $fillable = [
        'action',
        'status',
        'message',
        'meta',
        'user_id',
        'performed_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'performed_at' => 'datetime',
    ];

    // 🔥 MAGIC RELATION
    public function integratable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
