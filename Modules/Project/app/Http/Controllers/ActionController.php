<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Models\Action;
use App\Traits\ActivityLogTrait;

class ActionController extends Controller
{
    use ActivityLogTrait;

    /**
     * Display actions.
     */
    public function index()
    {
        $actions = Action::latest()->paginate(10);

        return view('actions.index', compact('actions'));
    }

    /**
     * Store action.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'type_key' => 'required|string|max:255|unique:actions,type_key',
        ]);

        Action::create([
            ...$validated,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Action created successfully'
        ]);
    }

    /**
     * Update action.
     */
    public function update(Request $request, int $id)
    {
        $action = Action::findOrFail($id);

        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'type_key' => 'required|string|max:255|unique:actions,type_key,' . $id,
        ]);

        $action->update([
            ...$validated,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Action updated successfully'
        ]);
    }

    /**
     * Delete action.
     */
    public function destroy(int $id)
    {
        Action::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Action deleted successfully'
        ]);
    }
}
