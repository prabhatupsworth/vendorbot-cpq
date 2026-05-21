<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Http\Requests\StoreCategoryRequest;
use Modules\Product\Models\Category;
use Throwable;
use App\Traits\ActivityLogTrait;
use Modules\Project\Models\Project;

class CategoryController extends Controller
{
    use ActivityLogTrait;
    /**
     * Display categories.
     */
    public function index()
    {
        try {

            $categories = Category::latest()
                ->paginate(20);

            $projects = Project::pluck('name', 'id');


            return view('product::categories.index', compact('categories', 'projects'));
        } catch (Throwable $e) {

            report($e);

            return back()->with('error', 'Unable to load categories.');
        }
    }

    /**
     * Store category.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {

            DB::beginTransaction();

            Category::create([
                ...$request->validated(),
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
            ]);
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create category.',
            ], 500);
        }
    }

    /**
     * Update category.
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        try {

            DB::beginTransaction();

            $category->update([

                ...$request->validated(),

                'is_required' => $request->boolean('is_required'),

                'has_tabs' => $request->boolean('has_tabs'),

                'has_default' => $request->boolean('has_default'),

                'active' => $request->boolean('active', true),

            ]);

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'Category updated successfully.',

            ]);
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Failed to update category.',

                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,

            ], 500);
        }
    }


    /**
     * Delete category.
     */
    public function destroy(Category $category)
    {
        try {

            DB::beginTransaction();

            $category->delete();

            DB::commit();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()->with('error', 'Failed to delete category.');
        }
    }
}
