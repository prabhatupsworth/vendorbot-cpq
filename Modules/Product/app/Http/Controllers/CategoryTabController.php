<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\CategoryTab;
use Throwable;

class CategoryTabController extends Controller
{
    public function index()
    {
        try {

            $tabs = CategoryTab::with('category')
                ->latest()
                ->paginate(20);

            return view('product::tabs.index', compact('tabs'));
        } catch (Throwable $e) {

            report($e);

            return back()->with('error', 'Unable to load tabs.');
        }
    }


    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'sort_order' => ['nullable', 'integer'],
            ]);

            DB::beginTransaction();

            CategoryTab::create([
                ...$validated,
                'is_default' => $request->boolean('is_default'),
                'active' => $request->boolean('active', true),
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('tabs.index')
                ->with('success', 'Tab created successfully.');
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to create tab.');
        }
    }
}
