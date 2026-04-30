<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceAccount extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_accounts';

    protected $fillable = [
        'type',
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
}
