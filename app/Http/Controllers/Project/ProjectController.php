<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\ActivityLog;
use App\Models\Invoice\InvoiceAccount;
use App\Models\PipeDrive\PipedriveAccount;
use App\Models\Project\Project;
use App\Models\Project\Smtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // ✅ LIST
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        // get all pipedrive accounts and invoice accounts for dropdowns
        $pipedriveAccounts = PipedriveAccount::pluck('account_name', 'id');
        $invoiceAccounts =   InvoiceAccount::pluck('type', 'id');

        return view('projects.index', compact('projects', 'pipedriveAccounts', 'invoiceAccounts'));
    }

    // ✅ STORE
    public function store(ProjectRequest $request)
    {

        try {
            $validated = $request->validated();

            $project = Project::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'website_url' => $validated['website_url'] ?? null,
                'event_name' => $validated['event_name'] ?? null,
                'flow_type' => $validated['flow_type'],
                'invoice_enabled' => $request->boolean('invoice_enabled'),
                'pipedrive_account_id' => $validated['pipedrive_account_id'] ?? null,
                'pipeline_id' => $validated['pipeline_id'] ?? null,
                'invoice_account_id' => $validated['invoice_account_id'] ?? null,
                'created_by' => Auth::id(),
            ]);

            activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project created successfully.',
            ]);
            return redirect()->back()->with('success', 'Project created successfully');
        } catch (\Exception $e) {

            Log::error('Project Store Error: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Something went wrong while creating project');
        }
    }

    // ✅ EDIT (for modal or page)
    public function edit(int $id)
    {
        try {
            $project = Project::findOrFail($id);

            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Project not found'], 404);
        }
    }

    // ✅ UPDATE
    public function update(ProjectRequest $request, int $id)
    {
        $validated = $request->validated();
        try {
            $project = Project::findOrFail($id);

            $project->update([
                'name' => $validated['name'],
                'website_url' => $validated['website_url'] ?? null,
                'event_name' => $validated['event_name'] ?? null,
                'flow_type' => $validated['flow_type'],
                'invoice_enabled' => $request->boolean('invoice_enabled'),
                'pipedrive_account_id' => $validated['pipedrive_account_id'] ?? null,
                'pipeline_id' => $validated['pipeline_id'] ?? null,
                'invoice_account_id' => $validated['invoice_account_id'] ?? null,

            ]);
            activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project updated successfully.',
            ]);

            return redirect()->back()->with('success', 'Project updated successfully');
        } catch (\Exception $e) {

            Log::error('Project Update Error: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Something went wrong while updating project');
        }
    }

    // ✅ DELETE (Soft Delete)
    public function destroy(int $id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project deleted successfully.',
            ]);
            return redirect()->back()->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {

            Log::error('Project Delete Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while deleting project');
        }
    }

    // ✅ SHOW (for project details page)
    public function show(int $id)
    {
        try {
            $project = Project::with(['pipedriveAccount:id,account_name', 'invoiceAccount:id,type', 'companyDetails', 'users:id,name', 'geoFilter'])->findOrFail($id);
            $allUsers = User::pluck('name', 'id')->toArray();
            $existingTypes = Smtp::where('project_id', $project->id)
                ->pluck('type') // already string
                ->toArray();

            $activityLog = ActivityLog::with('user:id,name')
                ->where('module', 'projects')
                ->where('record_id', $id)
                ->latest() // or orderBy('performed_at', 'desc')
                ->limit(5)
                ->get();
            // return response()->json(['project' => $project, 'activityLog' => $activityLog]);
            return view('projects.show', compact('project', 'activityLog', 'allUsers', 'existingTypes'));
        } catch (\Exception $e) {

            Log::error('Project Show Error: ' . $e->getMessage());

            return response()->json(['error' => 'Project not found'], 404);
        }
    }

    public function add_user(Request $request, int $projectId)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $project = Project::findOrFail($projectId);

        // 🔥 Just attach (no role)
        $project->users()->syncWithoutDetaching($validated['user_ids']);

        $users = User::whereIn('id', $validated['user_ids'])->get();

        $html = '';

        foreach ($users as $user) {
            $role = $user->getRoleNames()->first();
            $html .= view('projects.partials.users-card', [
                'user' => $user,
                'projectId' => $projectId,
                'role' => $role
            ])->render();
        }

        activityLog([
            'module' => 'projects',
            'action' => 'added',
            'record_id' => $project->id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Users added successfully.',
        ]);

        return response()->json([
            'status' => true,
            'action' => 'append',
            'target' => '#user-card',
            'message' => 'Users added successfully',
            'html' => $html
        ]);
    }

    public function remove_user(int $projectId, int $userId)
    {
        try {
            $project = Project::findOrFail($projectId);

            // 🔥 detach user from project
            $project->users()->detach($userId);

            activityLog([
                'module' => 'projects',
                'action' => 'added',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'User removed successfully.',
            ]);

            return response()->json([
                'status' => true,
                'action' => 'delete',
                'target' => '.user-card',
                'id' => $userId,
                'message' => 'User removed successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
