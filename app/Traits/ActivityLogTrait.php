<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait ActivityLogTrait
{
    /**
     * Save activity log
     */
    public function activityLog(array $data): void
    {
        ActivityLog::create([

            'user_id' => Auth::id(),

            'module' => $data['module'] ?? null,

            'record_id' => $data['record_id'] ?? null,

            'action' => $data['action'] ?? null,

            'status' => $data['status'] ?? null,

            'message' => $data['message'] ?? null,

            'meta' => $data['meta'] ?? null,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),

            'performed_at' => now(),
        ]);
    }
}
