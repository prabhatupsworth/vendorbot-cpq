<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Model;

class GeoFilter extends Model
{
    protected $fillable = [
        'project_id',
        'latitude_range',
        'longitude_range',
        'status',
    ];

    protected $casts = [
        'latitude_range' => 'float',
        'longitude_range' => 'float',
        'status' => 'boolean',
    ];

    // 🔗 belongs to project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
