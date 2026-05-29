<?php

namespace Modules\Draft\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Draft\Models\Draft;
use Modules\Draft\Models\DraftCategory;

class DraftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = DraftCategory::with('translations')
            ->orderBy('sort_order')
            ->get();

        $drafts = Draft::query()
            ->with(['category.translations'])

            ->when($request->filled('search'), function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where('subject', 'like', '%' . $request->search . '%')
                        ->orWhere('content', 'like', '%' . $request->search . '%');
                });
            })

            ->when($request->filled('category'), function ($query) use ($request) {

                $query->where(
                    'draft_category_id',
                    $request->category
                );
            })

            ->latest()

            ->paginate(20)

            ->withQueryString();

        return view(
            'draft::index',
            compact(
                'drafts',
                'categories'
            )
        );
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DraftCategory::with('translations')
            ->orderBy('sort_order')
            ->get();

        return view('draft::create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'draft_category_id' => ['required', 'exists:draft_categories,id'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        Draft::create($validated);

        return redirect()
            ->route('draft.index')
            ->with('success', 'Draft created successfully.');
    }


    /**
     * Show the specified resource.
     */
    public function show(Draft $draft)
    {
        $draft->load([
            'category.translations'
        ]);

        return view(
            'draft::show',
            compact('draft')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Draft $draft)
    {
        $categories = DraftCategory::with('translations')
            ->orderBy('sort_order')
            ->get();

        $placeholders = [
            '#Deal_Id#',
            '#Deal_City#',
            '#Deal_Pax#',
            '#Deal_Date#',
            '#Deal_Restaurant_StartTime#',
            '#Deal_Language#',
            '#Deal_Product_Name#',
            '#Deal_Product_Description#',
            '#Deal_Product2_Name#',
            '#Deal_Product2_Description#',
            '#Deal_Product_NettoCosts#',
            '#Deal_Product_GruttoCosts#',
            '#Deal_Data_Changes#',
            '#Supplier_Name#',
            '#Supplier_Address#',
            '#Supplier_Link_ReplyPage#',
            '#Restaurant_LatestMenu#',
            '#Supplier_Link_Blacklist#',
            '#Supplier_Link_ChangeData#',
            '#Supplier_Winner_Name#',
            '#Customer_Name#',
            '#Customer_Orga#',
            '#Customer_Link_ReplyPage#',
            '#Customer_LatestMenu#',
            '#Customer_Restaurant_List#',
            '#Decline_Link#',
        ];

        return view(
            'draft::edit',
            compact(
                'draft',
                'categories',
                'placeholders'
            )
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Draft $draft)
    {
        $validated = $request->validate([
            'draft_category_id' => ['required', 'exists:draft_categories,id'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $draft->update($validated);

        return redirect()
            ->route('draft.index')
            ->with('success', 'Draft updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Draft $draft)
    {
        $draft->delete();

        return back()->with('success', 'Draft deleted successfully.');
    }
}
