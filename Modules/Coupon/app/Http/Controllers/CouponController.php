<?php

namespace Modules\Coupon\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Coupon\Models\Coupon;
use Modules\Project\Models\Project;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                )

                    ->orWhere(
                        'code',
                        'like',
                        '%' . $request->search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            $request->status !== ''
        ) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('type') &&
            $request->type !== ''
        ) {

            $query->where(
                'type',
                $request->type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Coupons
        |--------------------------------------------------------------------------
        */

        $coupons = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'coupon::index',
            compact('coupons')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coupon::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' =>
            'required|string|max:255',

            'code' =>
            'required|string|max:255|unique:coupons,code',

            'amount' =>
            'required|numeric|min:0',

            'type' =>
            'required|in:amount,percentage',

            'usage_limit' =>
            'nullable|integer|min:1',

            'used_count' =>
            'nullable|integer|min:0',

            'min_order_value' =>
            'nullable|numeric|min:0',

            'valid_from' =>
            'nullable|date',

            'valid_until' =>
            'nullable|date|after_or_equal:valid_from',

            'description' =>
            'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Coupon
        |--------------------------------------------------------------------------
        */

        Coupon::create([

            'name' =>
            $request->name,

            'code' =>
            strtoupper(
                $request->code
            ),

            'amount' =>
            $request->amount,

            'type' =>
            $request->type,

            'per_person' =>
            $request->per_person
                ? 1 : 0,

            'usage_limit' =>
            $request->usage_limit,

            'used_count' =>
            $request->used_count
                ?? 0,

            'min_order_value' =>
            $request->min_order_value,

            'valid_from' =>
            $request->valid_from,

            'valid_until' =>
            $request->valid_until,

            'description' =>
            $request->description,

            'status' =>
            $request->status
                ? 1 : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('coupon.index')
            ->with(
                'success',
                'Coupon created successfully.'
            );
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupon::show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $coupon = Coupon::findOrFail($id);
        $projects = Project::all();
        return view(
            'coupon::edit',
            compact('coupon', 'projects')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $coupon = Coupon::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' =>
            'required|string|max:255',

            'code' =>
            'required|string|max:255|unique:coupons,code,' . $coupon->id,

            'amount' =>
            'required|numeric|min:0',

            'type' =>
            'required|in:amount,percentage',

            'usage_limit' =>
            'nullable|integer|min:1',

            'used_count' =>
            'nullable|integer|min:0',

            'min_order_value' =>
            'nullable|numeric|min:0',

            'valid_from' =>
            'nullable|date',

            'valid_until' =>
            'nullable|date|after_or_equal:valid_from',

            'description' =>
            'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $coupon->update([

            'name' =>
            $request->name,

            'code' =>
            strtoupper(
                $request->code
            ),

            'amount' =>
            $request->amount,

            'type' =>
            $request->type,

            'per_person' =>
            $request->per_person
                ? 1 : 0,

            'usage_limit' =>
            $request->usage_limit,

            'used_count' =>
            $request->used_count
                ?? 0,

            'min_order_value' =>
            $request->min_order_value,

            'valid_from' =>
            $request->valid_from,

            'valid_until' =>
            $request->valid_until,

            'description' =>
            $request->description,

            'status' =>
            $request->status
                ? 1 : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('coupon.index')
            ->with(
                'success',
                'Coupon updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return redirect()
            ->route('coupon.index')
            ->with(
                'success',
                'Coupon deleted successfully.'
            );
    }
    public function generateCode(Request $request)
    {
        $code = Coupon::generateCouponCode(
            Project::find(current_project_id())?->name,
            $request->coupon_name,
            $request->type,
            $request->amount
        );

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }
}
