<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        try {

            $products = Product::with([
                'category',
                'tab',
            ])
                ->latest()
                ->paginate(20);

            return view('product::products.index', compact('products'));
        } catch (Throwable $e) {

            report($e);

            return back()->with('error', 'Unable to load products.');
        }
    }


    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'project_id' => ['required', 'exists:projects,id'],
                'category_id' => ['required', 'exists:categories,id'],
                'tab_id' => ['nullable', 'exists:category_tabs,id'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric'],
                'cost' => ['nullable', 'numeric'],
            ]);

            DB::beginTransaction();

            $product = Product::create([
                ...$validated,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? 0,
                'is_default' => $request->boolean('is_default'),
                'is_pro' => $request->boolean('is_pro'),
                'show_only' => $request->boolean('show_only'),
                'active' => $request->boolean('active', true),
                'is_sync_backend' => $request->boolean('is_sync_backend'),
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()
                ->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to create product.');
        }
    }
}
