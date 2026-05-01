<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCompanyDetail extends Model
{
    use SoftDeletes;

    protected $table = 'project_company_details';

    protected $fillable = [
        'project_id',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'logo',
    ];

    // 🔗 Relation
    public function project()
    {
        return $this->belongsTo(\App\Models\Project\Project::class);
    }
}
