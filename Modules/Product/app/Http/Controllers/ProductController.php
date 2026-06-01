<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierCategoryRelationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Product\Models\Product;
use Modules\Product\Models\ScrapCategory;
use Modules\Project\Models\Project;
use Modules\Pipedrive\Models\PipedriveAccount;
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

            $curreenttProjectId = current_project_id();

            // getproject currency
            $currency_code= 'EUR';
            if ($curreenttProjectId) {
                $project = Project::find($curreenttProjectId);
                if ($project) {
                    $currency_code = $project->currency_code;
                }
            }

            return view('product::products.index', compact(
                'products',
                'projects',
                'currency_code'
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

                Product::create([

                ...$validated,

                'product_code' => $request->product_code,

                'pipedrive_product_id' => $request->pipedrive_product_id,

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
                'action' => 'replace',
                'target' => '#product-list',
                'message' => 'Product created successfully',
                'html' => view('product::products.partials.list', [
                    'products' => Product::with([
                        'project',
                        'category',
                        'tab',
                    ])->latest()->get(),
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

    // dd($request->all());
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
                'pipedrive_product_id' => $request->pipedrive_product_id,
                'product_code' => $request->product_code,
                'is_default' => $request->boolean('is_default'),

                'is_pro' => $request->boolean('is_pro'),

                'show_only' => $request->boolean('show_only'),

                'active' => $request->boolean('active', true),

                'is_sync_backend' => $request->boolean('is_sync_backend'),

            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'action' => 'replace',
                'target' => '#product-list',
                'id' => $product->id,
                'message' => 'Product updated successfully',
                'html' => view('product::products.partials.list', [
                    'products' => Product::with([
                        'project',
                        'category',
                        'tab',
                    ])->latest()->get(),
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

                'action' => 'delete',
                'target' => '.product-list',
                'id'     => $product->id,
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

    public function importProduct(Request $request)
    {

        $validated = $request->validate([

            'pipedrive_product_id' => [
                'required'
            ],

            'scrap_categories' => [
                'required',
                'array'
            ],
        ]);

        try {

            /*
        |--------------------------------------------------------------------------
        | Get Connected Account
        |--------------------------------------------------------------------------
        */

            $account = PipedriveAccount::where(
                'is_verified',
                true
            )->first();

            if (!$account) {

                return back()->with(
                    'error',
                    'Pipedrive account not connected'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Fetch Product From Pipedrive
        |--------------------------------------------------------------------------
        */

            $response = Http::timeout(120)
                ->acceptJson()
                ->get(

                    "{$account->base_url}/api/v2/products/{$validated['pipedrive_product_id']}",

                    [
                        'api_token' =>
                        $account->api_key
                    ]
                );

            /*
        |--------------------------------------------------------------------------
        | API Failed
        |--------------------------------------------------------------------------
        */

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    'Failed to fetch product from Pipedrive'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Product Data
        |--------------------------------------------------------------------------
        */

            $data = $response->json('data');


            if (!$data) {

                return back()->with(
                    'error',
                    'Product not found'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Product Price
        |--------------------------------------------------------------------------
        */

            $price = 0;

            if (!empty($data['prices'])) {

                $price = $data['prices'][0]['price'] ?? 0;
            }

            /*
        |--------------------------------------------------------------------------
        | Create / Update Product
        |--------------------------------------------------------------------------
        */

            $product = Product::updateOrCreate(

                [
                    'pipedrive_product_id' =>
                    $data['id'],
                ],

                [
                    'project_id' => 1,

                    'name' =>
                    $data['name'],

                    'description' =>
                    $data['description'] ?? null,

                    'price' => $price,

                    'cost' => $data['prices'][0]['cost'] ?? 0,

                    'currency' => $data['prices'][0]['currency'] ?? 'USD',

                    'active' => $data['active_flag'] ?? true,

                    'is_sync_backend' => true,
                ]
            );

            /*
        |--------------------------------------------------------------------------
        | Attach Categories
        |--------------------------------------------------------------------------
        */

            $product->scrapCategories()
                ->sync(
                    $validated['scrap_categories']
                );

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Product imported successfully'
                );
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}
