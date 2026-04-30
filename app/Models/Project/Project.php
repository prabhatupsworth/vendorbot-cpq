<?php

namespace App\Models\Project;

use App\Models\Invoice\InvoiceAccount;
use App\Models\PipeDrive\PipedriveAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid',
        'name',
        'slug',
        'website_url',
        'event_name',
        'flow_type',
        'invoice_enabled',
        'pipedrive_account_id',
        'invoice_account_id',
        'pipedrive_sync_status',
        'plugin_connected',
        'plugin_connected_at',
        'plugin_last_ping_at',
        'created_by',
    ];

    protected $casts = [
        'invoice_enabled' => 'boolean',
        'pipedrive_sync_status' => 'boolean',
        'plugin_connected' => 'boolean',
        'plugin_connected_at' => 'datetime',
        'plugin_last_ping_at' => 'datetime',
    ];

    // 🔗 Relations

    public function pipedriveAccount()
    {
        return $this->belongsTo(PipedriveAccount::class, 'pipedrive_account_id');
    }

    public function invoiceAccount()
    {
        return $this->belongsTo(InvoiceAccount::class, 'invoice_account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            $project->uid = Str::uuid();
        });
    }
}
