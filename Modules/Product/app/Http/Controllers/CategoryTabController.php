<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Category;
use Modules\Product\Models\CategoryTab;
use Throwable;
use Illuminate\Support\Facades\Validator;

class CategoryTabController extends Controller
{
    public function index()
    {
        try {

            $tabs = CategoryTab::with('category')
                ->latest()
                ->paginate(20);
            $categories = Category::where('active', true)
                ->pluck('name', 'id')
                ->toArray();
            return view('product::tabs.index', compact('tabs', 'categories'));
        } catch (Throwable $e) {

            report($e);

            return back()->with('error', 'Unable to load tabs.');
        }
    }


    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'category_id' => [
                    'required',
                    'exists:categories,id'
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                // 'sort_order' => [
                //     'nullable',
                //     'integer'
                // ],

            ]);

            // 🔥 Validation Error Response
            if ($validator->fails()) {

                return response()->json([

                    'status' => false,

                    'message' => 'Validation failed.',

                    'errors' => $validator->errors(),

                ], 422);
            }

            DB::beginTransaction();

            CategoryTab::create([

                ...$validator->validated(),

                'is_default' => $request->boolean('is_default'),

                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'Tab created successfully.',

                'redirect' => route('products.tabs.index'),

            ]);
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' => 'Failed to create tab.',

                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,

            ], 500);
        }
    }
}
