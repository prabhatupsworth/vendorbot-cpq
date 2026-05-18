<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Project\Models\Project;
use App\Models\User;

use Illuminate\Http\Request;

use App\Traits\ActivityLogTrait;

class ProjectUserController extends Controller
{
    use ActivityLogTrait;

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
            $html .= view('project::partials.users-card', [
                'user' => $user,
                'projectId' => $projectId,
                'role' => $role
            ])->render();
        }

        $this->activityLog([
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

            $this->activityLog([
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
