<?php

namespace App\Http\Controllers\ActivityLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function getModuleHistory(Request $request, $module, $recordId = null)
    {
        $query = ActivityLog::with('user:id,name')
            ->where('module', $module);

        if ($recordId) {
            $query->where('record_id', $recordId);
        }

        // Optional filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->from && $request->to) {
            $query->whereBetween('performed_at', [$request->from, $request->to]);
        }

        $logs = $query->latest()->paginate(15);

        // 👉 Return Blade instead of JSON
        return view('history.module-history', compact('logs', 'module', 'recordId'));
    }
}
