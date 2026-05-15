<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Product\Models\ScrapCategory;
use Modules\Project\Models\Project;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        try {

            $products = Product::with([
                'project',
                'category',
                'tab',
            ])
                ->latest()
                ->paginate(20);

            // Projects
            $projects = Project::pluck('name', 'id')
                ->toArray();


            return view('product::products.index', compact(
                'products',
                'projects',
            ));
        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to load products.'
            );
        }
    }

    public function create()
    {
        try {

            $scrapCategories = ScrapCategory::where('active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            return view(
                'product::products.create',
                compact('scrapCategories')
            );
        } catch (Throwable $e) {

            report($e);

            return back()->with(

                'error',

                'Unable to load product import form.'

            );
        }
    }
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'description' => [
                    'nullable',
                    'string'
                ],

                'price' => [
                    'required',
                    'numeric'
                ],

                'cost' => [
                    'nullable',
                    'numeric'
                ],

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

            return response()->json([
                'status' => true,
                'action' => 'append',
                'target' => '#product-list',
                'message' => 'Product created successfully',
                'html' => view('product::products.partials.list', [
                    'product' => $product,
                ])->render(),
            ]);
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Failed to create product.',

                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,

            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {

        try {

            $validated = $request->validate([

                'project_id' => [
                    'required',
                    'exists:projects,id'
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'description' => [
                    'nullable',
                    'string'
                ],

                'price' => [
                    'required',
                    'numeric'
                ],

                'cost' => [
                    'nullable',
                    'numeric'
                ],

                // 🔥 REQUIRED
                'discount_type' => [
                    'nullable',
                    'in:fixed,percent'
                ],

                'discount_value' => [
                    'nullable',
                    'numeric'
                ],

            ]);

            DB::beginTransaction();

            $product->update([

                ...$validated,

                'is_default' => $request->boolean('is_default'),

                'is_pro' => $request->boolean('is_pro'),

                'show_only' => $request->boolean('show_only'),

                'active' => $request->boolean('active', true),

                'is_sync_backend' => $request->boolean('is_sync_backend'),

            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'action' => 'update',
                'target' => '.product-list',
                'id' => $product->id,
                'message' => 'Product updated successfully',
                'html' => view('product::products.partials.list', [
                    'product' => $product,
                ])->render(),
            ]);
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Failed to update product.',

                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,

            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {

            $product->delete();

            return response()->json([

                'status' => true,

                'message' => 'Product deleted successfully.'

            ]);
        } catch (Throwable $e) {

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Failed to delete product.',

                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,

            ], 500);
        }
    }
}
