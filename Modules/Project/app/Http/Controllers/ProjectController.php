<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Currency;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\InvoiceAccount;
use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Models\PipedriveField;
use Modules\Project\Http\Requests\Project\ProjectRequest;
use Modules\Project\Models\Action;
use Modules\Project\Models\Project;
use Modules\Project\Models\Smtp;
use App\Traits\ActivityLogTrait;
use Illuminate\Validation\ValidationException;


class ProjectController extends Controller
{
    use ActivityLogTrait;


    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            $query = Project::query();
            
            // 🔥 role based visibility
            if (!$user->hasRole('super_admin')) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }

            // 🔍 search
            if ($request->filled('search')) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('website_url', 'like', "%{$search}%")
                        ->orWhere('event_name', 'like', "%{$search}%");
                });
            }

            $projects = $query
                ->with([
                    'pipedriveAccount',
                    'invoiceAccount'
                ])
                ->latest()
                ->paginate(10)
                ->withQueryString();

            // ajax
            if ($request->ajax()) {

                $html = view(
                    'project::partials.table',
                    compact('projects')
                )->render();

                return response()->json([
                    'html' => $html
                ]);
            }

            $pipedriveAccounts = PipedriveAccount::pluck(
                'account_name',
                'id'
            );

            $invoiceAccounts = InvoiceAccount::pluck(
                'type',
                'id'
            );

            $currencies = Currency::pluck('name', 'code')->toArray();

            $languages = Language::pluck('name', 'code')->toArray();

            return view(
                'project::index',
                compact(
                    'projects',
                    'currencies',
                    'languages',
                    'pipedriveAccounts',
                    'invoiceAccounts'
                )
            );

        }

        $project_id = current_project_id();
        $project = Project::where('id',$project_id)->whereHas('users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->first();
    
        if (!@$project->id) {
            return redirect()->route('projects.index')->with('error', 'Project not assigend');
        }
    
        return $this->show($project->id);


    }
    // ✅ STORE
    public function store(ProjectRequest $request)
    {

        try {
            $validated = $request->validated();
            // 1. Generate the slug manually first
            $slug = Str::slug($validated['name']);
            // 2. Check if this slug already exists in the database
            $deletedProject = Project::onlyTrashed()
                ->where('slug', $slug)
                ->first();

            if ($deletedProject) {
                throw ValidationException::withMessages([
                    'name' => ['This project was deleted. Please restore it or use another name.']
                ]);
            }

            if (Project::withoutTrashed()->where('slug', $slug)->exists()) {
                throw ValidationException::withMessages([
                    'name' => ['A project with this name already exists.']
                ]);
            }
            $project = Project::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'website_url' => $validated['website_url'] ?? null,
                'event_name' => $validated['event_name'] ?? null,
                'currency_code' => $validated['currency_code'] ?? null,
                'language_code' => $validated['language_code'] ?? null,
                'vat' => $validated['vat'] ?? 0,
                'vat_status' => $validated['vat_status'] ?? 0,
                'flow_type' => $validated['flow_type'],
                'invoice_enabled' => $request->boolean('invoice_enabled'),
                'pipedrive_account_id' => $validated['pipedrive_account_id'] ?? null,
                'pipeline_id' => $validated['pipeline_id'] ?? null,
                'invoice_account_id' => $validated['invoice_account_id'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Assign only super admin(s)
            $superAdminIds = User::role('super_admin')->pluck('id')->toArray();

            $project->users()->sync($superAdminIds);

            $this->activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project created successfully.',
            ]);

            return response()->json([
                'status' => true,
                'action' => 'prepend',
                'target' => '#project-table-body',
                'message' => 'Project created successfully',
                'html' => view('project::partials.list', [
                    'project' => $project,
                ])->render(),
            ]);
        } catch (ValidationException $e) {

            throw $e; // Return 422 with errors object

        } catch (\Exception $e) {

            Log::error('Project Store Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while creating project.',
            ], 500);
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
        $slug = Str::slug($validated['name']);

        // Check deleted project with same slug
        $deletedProject = Project::onlyTrashed()
            ->where('slug', $slug)
            ->where('id', '!=', $id)
            ->first();

        if ($deletedProject) {
            throw ValidationException::withMessages([
                'name' => [
                    'This project was deleted. Please restore it or use another name.'
                ]
            ]);
        }

        // Check active project with same slug
        if (
            Project::withoutTrashed()
            ->where('slug', $slug)
            ->where('id', '!=', $id)
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'name' => [
                    'A project with this name already exists.'
                ]
            ]);
        }
        try {
            $project = Project::findOrFail($id);

            $project->update([
                'name' => $validated['name'],
                'website_url' => $validated['website_url'] ?? null,
                'event_name' => $validated['event_name'] ?? null,
                'currency_code' => $validated['currency_code'] ?? null,
                'language_code' => $validated['language_code'] ?? null,
                'vat' => $validated['vat'] ?? 0,
                'vat_status' => $validated['vat_status'] ?? 0,
                'flow_type' => $validated['flow_type'],
                'invoice_enabled' => $request->boolean('invoice_enabled'),
                'pipedrive_account_id' => $validated['pipedrive_account_id'] ?? null,
                'pipeline_id' => $validated['pipeline_id'] ?? null,
                'invoice_account_id' => $validated['invoice_account_id'] ?? null,

            ]);
            $this->activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project updated successfully.',
            ]);

            // return redirect()->back()->with('success', 'Project updated successfully');
            return response()->json([
                'status' => true,
                'action' => 'update',
                'target' => '.project-list',
                'id' => $project->id,
                'message' => 'Project updated successfully',
                'html' => view('project::partials.list', [
                    'project' => $project,
                ])->render(),
            ]);
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
            $this->activityLog([
                'module' => 'projects',
                'action' => 'created',
                'record_id' => $project->id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Project deleted successfully.',
            ]);
            // return redirect()->back()->with('success', 'Project deleted successfully');
            return response()->json([
                'status' => true,
                'action' => 'delete',
                'target' => '.project-list',
                'id'     => $id,
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('Project Delete Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while deleting project');
        }
    }


    public function show(int $id)
    {
        $user = auth()->user();
        try {

            $query = Project::with([
                'pipedriveAccount:id,account_name',
                'invoiceAccount:id,type',
                'companyDetails',
                'users:id,name,email',
                'geoFilter'
            ]);

            if (!$user->hasRole('super-admin')) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }

            $project = $query->findOrFail($id);


            $stages = $project->pipeline
                ?->stages()
                ->where(
                    'pipedrive_account_id',
                    $project->pipedrive_account_id
                )
                ->orderBy('stage_id')
                ->pluck(
                    'name',
                    'stage_id'
                );

            $actions = Action::pluck(
                'action_name',
                'type_key'
            );

            /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

            $allUsers = User::pluck('name', 'id')->toArray();

            /*
        |--------------------------------------------------------------------------
        | SMTP Existing Types
        |--------------------------------------------------------------------------
        */

            $existingTypes = Smtp::where('project_id', $project->id)
                ->pluck('type')
                ->toArray();

            /*
        |--------------------------------------------------------------------------
        | ONLY PROJECT ACCOUNT FIELDS
        |--------------------------------------------------------------------------
        */

            $pipedriveFields = PipedriveField::where(
                'pipedrive_account_id',
                $project->pipedrive_account_id
            )
                ->orderBy('name')
                ->pluck(
                    'name',
                    'field_key'
                );

            /*
        |--------------------------------------------------------------------------
        | ONLY PROJECT ACCOUNT STAGES
        |--------------------------------------------------------------------------
        */

            /*
        |--------------------------------------------------------------------------
        | System Fields
        |--------------------------------------------------------------------------
        */

            $systemFields = config('system_fields');

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $activityLog = ActivityLog::with('user:id,name')
                ->where('module', 'projects')
                ->where('record_id', $id)
                ->latest()
                ->limit(5)
                ->get();
            $selectedUsers = $project->users
                ->pluck('id')
                ->toArray();
            return view('project::show', compact(

                'project',
                'actions',
                'stages',
                'activityLog',
                'allUsers',
                'existingTypes',
                'pipedriveFields',
                'systemFields',
                'selectedUsers'

            ));
        } catch (\Exception $e) {

            Log::error(
                'Project Show Error: ' . $e->getMessage()
            );

            // return response()->back([
            //     'error' => 'Project not found'
            // ], 404);
            return redirect()->route('projects.index')->with('error', 'Project not assigend');
        }
    }

    public function selectedUsers(Project $project)
    {
        return response()->json([
            'selected_users' => $project->users()
                ->pluck('users.id')
                ->toArray()
        ]);
    }
}
