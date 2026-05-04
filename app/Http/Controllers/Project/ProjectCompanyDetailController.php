<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectCompanyRequest;
use App\Models\Project\ProjectCompanyDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProjectCompanyDetailController extends Controller
{
    public function store(StoreProjectCompanyRequest $request, $projectId)
    {
        // dd($request->all(), $request->file('logo'));
        $validated = $request->validated();
        // dd($request->all());
        try {

            $logoPath = null;

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('company_logos', 'public');
            }

            $company =  ProjectCompanyDetail::updateOrCreate(
                ['project_id' => $projectId],
                array_merge($validated, [
                    'logo' => $logoPath
                        ?? ProjectCompanyDetail::where('project_id', $projectId)->value('logo')
                ])
            );

            return response()->json([
                'status' => true,
                'message' => 'Company saved successfully',
                'data' => $company
            ]);
        } catch (\Exception $e) {

            Log::error('Project Store Error: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Something went wrong while creating project');
        }
    }

    // ✅ SHOW (for view modal)
    public function show($project_id)
    {
        $company = ProjectCompanyDetail::where('project_id', $project_id)->first();

        return response()->json($company);
    }

    // ✅ DELETE LOGO (optional)
    public function deleteLogo($id)
    {
        try {
            $company = ProjectCompanyDetail::findOrFail($id);

            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $company->update(['logo' => null]);

            return back()->with('success', 'Logo removed successfully');
        } catch (\Exception $e) {

            Log::error('Delete Logo Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete logo');
        }
    }
}
