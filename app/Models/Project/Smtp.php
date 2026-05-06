<?php

namespace App\Models\Project;

use App\Enums\SmtpType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    use HasFactory;

    protected $table = 'smtps';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'project_id',
        'type',
        'is_active',
        'connected',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_email',
        'from_name',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'port' => 'integer',
        'type' => SmtpType::class,
    ];

    /**
     * Relationship: SMTP belongs to Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Hide sensitive fields
     */
    protected $hidden = [
        'password',
    ];
}
