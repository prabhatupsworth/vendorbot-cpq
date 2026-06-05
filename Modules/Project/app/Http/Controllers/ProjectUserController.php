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
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::findOrFail($projectId);

        // Sync users (add new + remove unchecked)
        // $project->users()->sync($validated['user_ids']);
        $project->users()->syncWithoutDetaching($validated['user_ids']);

        // Reload relationship
        $project->load([
            'users' => function ($q) {
                $q->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'super_admin');
                });
            }
        ]);

        $html = view('project::partials.users-card', [
            'users'     => $project->users,
            'projectId' => $projectId,
        ])->render();

        $this->activityLog([
            'module'       => 'projects',
            'action'       => 'updated',
            'record_id'    => $project->id,
            'performed_at' => now(),
            'status'       => 'success',
            'message'      => 'Project users updated successfully.',
        ]);

        return response()->json([
            'status'  => true,
            'action'  => 'replace',
            'target'  => '#user-card',
            'message' => 'Project users updated successfully.',
            'html'    => $html,
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
                'selected_users' => $project->users()
                    ->pluck('users.id')
                    ->toArray(),
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
