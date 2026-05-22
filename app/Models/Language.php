<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Project\Models\Project;

class Language extends Model
{
    protected $table = 'languages';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Projects Relation
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'language_code', 'code');
    }
}
