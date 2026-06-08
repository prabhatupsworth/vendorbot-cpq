<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            ])
                ->latest()
                ->paginate(20);

            // Projects
            $projects = Project::pluck('name', 'id')
                ->toArray();

            $curreenttProjectId = current_project_id();

            // getproject currency
            $currency_code = 'EUR';
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
            $projectId = current_project_id();
            $scrapCategories = ScrapCategory::whereHas('supplierRelations', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })->pluck('name', 'id')
                ->toArray();

            return view(
                'product::products.create',
                compact('scrapCategories')
            );
        } catch (Throwable $e) {

            report($e);

            return back()->with(

                'error',

                'Unable to load product create form.'

            );
        }
    }


    public function edit(Product $product)
    {
        $projectId = current_project_id();
        $scrapCategories = ScrapCategory::whereHas('supplierRelations', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->pluck('name', 'id')
            ->toArray();


        return view('product::products.edit', [
            'product' => $product->load('scrapCategories'),
            'scrapCategories' => $scrapCategories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'crm_product_id' => 'required|string|max:255',

            'title' => 'required|string|max:255',

            'sub_title' => 'nullable|string|max:255',

            'product_code' => 'nullable|string|max:255',

            'price' => 'required|numeric',

            'cost' => 'nullable|numeric',

            'description' => 'nullable|string',

            'proposal_desc' => 'nullable|string',

            'scrap_categories' => 'required|array|min:1',

            'scrap_categories.*' => 'exists:scrap_categories,id',

        ]);

        DB::beginTransaction();

        try {

            $product = Product::create([

                // 'project_id' => current_project_id(),

                'crm_product_id' => $validated['crm_product_id'],

                'title' => $validated['title'],

                'sub_title' => $validated['sub_title'] ?? null,

                'product_code' => $validated['product_code'] ?? null,

                'cost' => $validated['cost'] ?? null,

                'price' => $validated['price'],

                'currency_code' => active_currency_code(),

                'description' => $validated['description'] ?? null,

                'proposal_desc' => $validated['proposal_desc'] ?? null,

                'is_best_seller' => $request->boolean('is_best_seller'),

                'active' => 1,

                'is_sync_backend' => 0,
            ]);

            $product->scrapCategories()->sync(
                $request->scrap_categories
            );

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product.');
        }
    }

    public function update(Request $request, Product $product)
    {

        $validated = $request->validate([

            'crm_product_id' => 'required|string|max:255',

            'title' => 'required|string|max:255',

            'sub_title' => 'nullable|string|max:255',

            'product_code' => 'nullable|string|max:255',

            'price' => 'required|numeric',

            'cost' => 'nullable|numeric',

            'description' => 'nullable|string',

            'proposal_desc' => 'nullable|string',

            'scrap_categories' => 'required|array|min:1',

            'scrap_categories.*' => 'exists:scrap_categories,id',
        ]);

        DB::beginTransaction();

        try {

            $product->update([

                'crm_product_id' => $validated['crm_product_id'],

                'title' => $validated['title'],

                'sub_title' => $validated['sub_title'] ?? null,

                'product_code' => $validated['product_code'] ?? null,

                'cost' => $validated['cost'] ?? null,

                'price' => $validated['price'],

                'description' => $validated['description'] ?? null,

                'proposal_desc' => $validated['proposal_desc'] ?? null,

                'is_best_seller' => $request->boolean('is_best_seller'),
            ]);

            $product->scrapCategories()->sync(
                $request->scrap_categories
            );

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product.');
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


    public function import()
    {
        try {

            $projectId = current_project_id();
            $scrapCategories = ScrapCategory::whereHas('supplierRelations', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })->pluck('name', 'id')
                ->toArray();

            return view(
                'product::products.import',
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

    public function importProduct(Request $request)
    {

        $validated = $request->validate([

            'crm_product_id' => [
                'required'
            ],

            'scrap_categories' => [
                'nullable',
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
                    "https://api.pipedrive.com/v1/products/{$validated['crm_product_id']}",
                    [
                        'api_token' => $account->api_key,
                    ]
                );

            if ($response->status() === 404) {

                return back()->with(
                    'error',
                    'The selected product no longer exists in Pipedrive. Please refresh products and try again.'
                );
            }

            if (! $response->successful()) {

                return back()->with(
                    'error',
                    'Failed to fetch product from Pipedrive.'
                );
            }

            $data = $response->json('data');

            if (! $data) {

                return back()->with(
                    'error',
                    'The selected product was not found in Pipedrive.'
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
                    'crm_product_id' =>
                    $data['id'],
                ],

                [
                    'project_id' => current_project_id(),

                    'title' => $data['name'],

                    'product_code' => $data['product_code'],

                    'description' => $data['description'] ?? null,

                    'price' => $price,

                    'cost' => $data['prices'][0]['cost'] ?? 0,

                    'currency_code' => $data['prices'][0]['currency'] ?? 'USD',

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
