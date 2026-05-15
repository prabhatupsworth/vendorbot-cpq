<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Project\Models\Project;

class ProjectSwitcherController extends Controller
{
    /**
     * Switch current project.
     */
    public function switch(Request $request)
    {
        $request->validate([

            'project_id' => [
                'required',
                'exists:projects,id'
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify Access
        |--------------------------------------------------------------------------
        */

        $project = Project::findOrFail(
            $request->project_id
        );

        auth()->user()->update([

            'current_project_id' => $project->id

        ]);

        return back()->with(

            'success',

            'Project switched successfully.'

        );
    }
}
