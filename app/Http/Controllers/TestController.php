<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Invoice\InvoiceAccount;
use App\Models\PipeDrive\PipedriveAccount;
use App\Models\Project\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class TestController extends Controller
{
    // ✅ Show page
    public function index()
    {
        $projects = Project::latest()->get();
        $pipedriveAccounts = PipedriveAccount::pluck('account_name', 'id');
        $invoiceAccounts = InvoiceAccount::pluck('type', 'id');
        return view('test.index', compact('projects', 'pipedriveAccounts', 'invoiceAccounts'));
    }

    public function list()
    {
        return response()->json([
            "projects" => Project::latest()->get()
        ]);
    }

    // ✅ Store (Create)
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();

        // 🔥 slug add karo
        $validated['slug'] = Str::slug($validated['name']);

        $project = Project::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ]);
    }

    // ✅ Update (Edit)
    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            // ✅ already validated
            $data = $request->validated();

            // 🔥 extra fields handle
            $data['slug'] = Str::slug($data['name']) . '-' . time();
            $data['invoice_enabled'] = $request->boolean('invoice_enabled');

            $project->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project
            ]);
        } catch (\Exception $e) {

            Log::error('Project Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating project'
            ], 500);
        }
    }

    // ✅ Optional: Show single (API based edit)
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return response()->json($project);
    }

    // ✅ Delete (optional)
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
