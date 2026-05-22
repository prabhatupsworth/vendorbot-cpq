<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Project\Models\Project;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'symbol',
        'name',
    ];

    /**
     * Projects Relation
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'currency_code', 'code');
    }
}
