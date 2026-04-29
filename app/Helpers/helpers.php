<?php

if (!function_exists('user_status_badge')) {
    function user_status_badge($status)
    {
        return match ($status) {
            1 => '<span class="badge bg-success">Active</span>',
            0 => '<span class="badge bg-danger">Inactive</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}

if (!function_exists('activityLog')) {
    function activityLog(array $data)
    {
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),

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
