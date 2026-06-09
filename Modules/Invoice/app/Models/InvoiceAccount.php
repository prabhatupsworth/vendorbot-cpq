<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Project\Models\Project;

class InvoiceAccount extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_accounts';

    protected $fillable = [
        'type',
        'account_name',
        'api_key',
        'base_url',
        'is_verified',
        'default_tax',
        'currency',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'default_tax' => 'decimal:2',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'invoice_account_id');
    }
}
