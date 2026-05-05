<?php

namespace App\Models\Project;

use App\Models\Invoice\InvoiceAccount;
use App\Models\PipeDrive\PipedriveAccount;
use App\Models\PipeDrive\PipedrivePipeline;
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
        'is_active',
        'website_url',
        'event_name',
        'flow_type',
        'invoice_enabled',
        'pipedrive_account_id',
        'pipeline_id',
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

    public function pipeline()
    {
        return $this->belongsTo(PipedrivePipeline::class, 'pipeline_id');
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

    public function companyDetails()
    {
        return $this->hasOne(ProjectCompanyDetail::class, 'project_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users')
            ->withTimestamps();
    }

    public function smtps()
    {
        return $this->hasMany(Smtp::class);
    }

    public function geoFilter()
    {
        return $this->hasOne(GeoFilter::class);
    }
}
