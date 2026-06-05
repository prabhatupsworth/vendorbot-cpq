<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\ScrapCategory;
use Modules\Supplier\Enums\SupplierStatusEnum;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Models\SupplierCategoryRelationship;

class SupplierController extends Controller
{



    public function index(Request $request)
    {
        $query = Supplier::with([
            'countryData',
            'categories'
        ]);

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
    |--------------------------------------------------------------------------
    | CATEGORY FILTER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('category')) {

            $query->whereHas(
                'categories',
                function ($q) use ($request) {

                    $q->whereIn(
                        'scrap_categories.id',
                        $request->category
                    );
                }
            );
        }

        $suppliers = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = SupplierStatusEnum::cases();

        $categories = ScrapCategory::orderBy('name')
            ->get();

        $countries = Country::where('status', 1)
            ->pluck('name', 'code');
        $importCategories = ScrapCategory::where('active', 1)
            ->orderBy('name')
            ->pluck('name', 'scraper_category_id');

        return view(
            'supplier::index',
            compact(
                'suppliers',
                'statuses',
                'categories',
                'countries',
                'importCategories'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();

        $categories = ScrapCategory::orderBy('name')->get();

        $days = [
            'mo' => 'Monday',
            'di' => 'Tuesday',
            'mi' => 'Wednesday',
            'do' => 'Thursday',
            'fr' => 'Friday',
            'sa' => 'Saturday',
            'so' => 'Sunday',
        ];

        return view(
            'supplier::create',
            compact(
                'countries',
                'categories',
                'days'
            )
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'city' => 'nullable|string|max:255',

            'country' => 'nullable|string|max:10',

            'status' => 'nullable|integer',

            'cp_title' => 'nullable|string',

            'cp_name' => 'nullable|string|max:255',

            'capacity' => 'nullable|integer',

            'email' => 'nullable|email|max:255',

            'phone' => 'nullable|string|max:50',

            'url' => 'nullable|string|max:255',

            'social_facebook' => 'nullable|string|max:255',

            'social_instagram' => 'nullable|string|max:255',

            'street' => 'nullable|string|max:255',

            'zip' => 'nullable|string|max:50',

            'lat' => 'nullable|string|max:50',

            'lon' => 'nullable|string|max:50',

            'notice' => 'nullable|string',

            'notice_intern' => 'nullable|string',

            'categories' => 'nullable|array',

            'categories.*' => 'exists:scrap_categories,id',

            'days_off' => 'nullable|array',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DAYS OFF
        |--------------------------------------------------------------------------
        */

        $daysOff = [];

        if ($request->filled('days_off')) {

            foreach ($request->days_off as $day) {

                $daysOff[$day] = 1;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE SUPPLIER
        |--------------------------------------------------------------------------
        */

        $supplier = Supplier::create([

            'name' => $request->name,

            'city' => $request->city,

            'country' => $request->country,

            'status' => $request->status,

            'cp_title' => $request->cp_title,

            'cp_name' => $request->cp_name,

            'capacity' => $request->capacity,

            'email' => $request->email,

            'phone' => $request->phone,

            'url' => $request->url,

            'social_facebook' => $request->social_facebook,

            'social_instagram' => $request->social_instagram,

            'street' => $request->street,

            'zip' => $request->zip,

            'lat' => $request->lat,

            'lon' => $request->lon,

            'notice' => $request->notice,

            'notice_intern' => $request->notice_intern,

            'daysoff' => json_encode($daysOff),
        ]);

        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        if ($request->filled('categories')) {

            $supplier->categories()->sync(
                $request->categories
            );
        }

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier created successfully.'
            );
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $supplier = Supplier::with('countryData', 'categories')->findOrFail($id);
        return view('supplier::show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $countries = Country::where(
            'status',
            1
        )->orderBy('name')
            ->get();
        $supplier = Supplier::with('countryData', 'categories')->findOrFail($id);
        $categories = ScrapCategory::all();

        return view('supplier::edit', compact('supplier', 'categories', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
        // dd($request->all());
        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'nullable|email',

            'phone' => 'nullable|string|max:255',

            'city' => 'nullable|string|max:255',

            'country' => 'nullable|string|max:255',

            'street' => 'nullable|string|max:255',

            'zip' => 'nullable|string|max:255',

            'lat' => 'nullable',

            'lon' => 'nullable',

            'url' => 'nullable',

            'social_facebook' => 'nullable',

            'social_instagram' => 'nullable',

            'status' => 'nullable|string',

            'capacity' => 'nullable|numeric',

            'cp_name' => 'nullable|string|max:255',

            'cp_title' => 'nullable|string|max:255',

            'categories' => 'nullable|array',

            'days_off.*' => 'in:mo,di,mi,do,fr,sa,so',
            'notice' => 'nullable|string',
            'notice_intern' => 'nullable|string',

        ]);

        // dd($request->all());

        /*
    |--------------------------------------------------------------------------
    | Find Supplier
    |--------------------------------------------------------------------------
    */

        $supplier = Supplier::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | Days Off
    |--------------------------------------------------------------------------
    */

        $daysMap = [

            'mo',
            'di',
            'mi',
            'do',
            'fr',
            'sa',
            'so'
        ];

        $daysOff = [];
        foreach ($daysMap as $day) {

            /*
        |--------------------------------------------------------------------------
        | Checked = Open = 1
        | Unchecked = Closed = 0
        |--------------------------------------------------------------------------
        */

            $daysOff[$day] =
                in_array(
                    $day,
                    $request->days_off ?? []
                ) ? 1 : 0;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Supplier
    |--------------------------------------------------------------------------
    */

        $supplier->update([

            'name' =>
            $request->name,

            'city' =>
            $request->city,

            'email' =>
            $request->email,

            'phone' =>
            $request->phone,

            'url' =>
            $request->url,

            'social_facebook' =>
            $request->social_facebook,

            'social_instagram' =>
            $request->social_instagram,

            'country' =>
            strtolower($request->country),

            'zip' =>
            $request->zip,

            'street' =>
            $request->street,

            'lon' =>
            $request->lon,

            'lat' =>
            $request->lat,

            'capacity' =>
            $request->capacity,

            'cp_name' =>
            $request->cp_name,

            'cp_title' =>
            $request->cp_title,

            'status' => $request->status,

            'notice' => $request->notice,
            'notice_intern' => $request->notice_intern,

            /*
        |--------------------------------------------------------------------------
        | Days Off JSON
        |--------------------------------------------------------------------------
        */

            'daysoff' =>
            json_encode($daysOff),
        ]);

        /*
    |--------------------------------------------------------------------------
    | Sync Categories
    |--------------------------------------------------------------------------
    */

        $syncCategories = [];

        if (!empty($request->categories)) {

            foreach ($request->categories as $categoryId) {

                $syncCategories[$categoryId] = [

                    'is_main' => 0
                ];
            }
        }

        $supplier->categories()->sync(
            $syncCategories
        );

        /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route(
                'suppliers.index'
            )
            ->with(

                'success',

                'Supplier updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier deleted successfully.'
            );
    }


    public function importSuppliers(Request  $request)
    {

        $validated = $request->validate([
            'country_code' => ['required', 'string'],
            'city'         => ['required', 'string'],
            'types'        => ['required', 'array'],
            'types.*'      => ['required', 'string'],
        ]);
        /*
    |--------------------------------------------------------------------------
    | Scrap.io API URL
    |--------------------------------------------------------------------------
    */

        $url = 'https://scrap.io/api/v2/map/search';

        $headers = [
            'Authorization: Bearer 358|B9SVH5H4Wunxbz2ZYbKt8Ymz9IltOBUaVr2pXpIP59010617',
            'Content-Type: application/json',
        ];

        /*
    |--------------------------------------------------------------------------
    | Request Body
    |--------------------------------------------------------------------------
    */

        $params = [

            'country_code' => $validated['country_code'],
            'city'         => $validated['city'],
            'types' => $validated['types'],

            'gmap_is_closed' => false,

            'gmap_reviews_rating_gte' => 4,

            'gmap_has_website' => true,

            'gmap_has_phone' => true,

            'website_has_emails' => true,

            'per_page' => 50
        ];
        /*
    |--------------------------------------------------------------------------
    | Cursor Pagination
    |--------------------------------------------------------------------------
    */

        $cursor = null;

        do {

            if (!empty($cursor)) {
                $params['cursor'] = $cursor;
            }

            /*
        |--------------------------------------------------------------------------
        | cURL Request
        |--------------------------------------------------------------------------
        */

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);

            curl_setopt($curl, CURLOPT_POST, true);

            curl_setopt(
                $curl,
                CURLOPT_POSTFIELDS,
                json_encode($params)
            );

            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                $headers
            );

            curl_setopt(
                $curl,
                CURLOPT_RETURNTRANSFER,
                true
            );

            /*
        |--------------------------------------------------------------------------
        | Execute Request
        |--------------------------------------------------------------------------
        */

            $response = curl_exec($curl);

            /*
        |--------------------------------------------------------------------------
        | Error Handling
        |--------------------------------------------------------------------------
        */

            if (curl_errno($curl)) {

                return response()->json([

                    'success' => false,

                    'message' => curl_error($curl)
                ]);
            }

            curl_close($curl);

            /*
        |--------------------------------------------------------------------------
        | Decode Response
        |--------------------------------------------------------------------------
        */

            $json = json_decode($response, true);

            /*
        |--------------------------------------------------------------------------
        | Next Cursor
        |--------------------------------------------------------------------------
        */

            $cursor = data_get(
                $json,
                'meta.next_cursor'
            );

            /*
        |--------------------------------------------------------------------------
        | Response Data
        |--------------------------------------------------------------------------
        */

            $data = $json['data'] ?? [];

            foreach ($data as $supplierData) {

                DB::transaction(function () use ($supplierData) {

                    /*
                |--------------------------------------------------------------------------
                | Main Email
                |--------------------------------------------------------------------------
                */

                    $mainEmail = null;

                    if (
                        !empty($supplierData['website_data']['emails']) &&
                        isset(
                            $supplierData['website_data']['emails'][0]['email']
                        )
                    ) {

                        $mainEmail =
                            $supplierData['website_data']['emails'][0]['email'];
                    }

                    /*
                |--------------------------------------------------------------------------
                | Insert / Update Supplier
                |--------------------------------------------------------------------------
                */
                    $workingHours =
                        $supplierData['working_hours'] ?? [];

                    $daysMap = [

                        'monday' => 'mo',

                        'tuesday' => 'di',

                        'wednesday' => 'mi',

                        'thursday' => 'do',

                        'friday' => 'fr',

                        'saturday' => 'sa',

                        'sunday' => 'so',
                    ];

                    $daysOff = [];

                    foreach ($daysMap as $apiDay => $shortDay) {

                        $value = $workingHours[$apiDay] ?? null;

                        /*
                        |--------------------------------------------------------------------------
                        | If Closed => 0
                        | Otherwise => 1
                        |--------------------------------------------------------------------------
                         */

                        if (
                            is_string($value) &&
                            strtolower($value) === 'closed'
                        ) {

                            $daysOff[$shortDay] = 0;
                        } else {

                            $daysOff[$shortDay] = 1;
                        }
                    }
                    $email = $supplierData['emails'][0]['email'] ?? null;
                    $city  = $supplierData['location_city'] ?? null;
                    $supplier = Supplier::updateOrCreate(

                        [
                            'email' => $email,
                            'city'  => $city,
                        ],

                        [

                            'name' =>
                            $supplierData['name'] ?? null,

                            'city' =>
                            $supplierData['location_city']
                                ?? null,

                            'email' =>
                            $mainEmail,

                            'phone' =>
                            $supplierData['phone']
                                ?? null,

                            'url' =>
                            $supplierData['website']
                                ?? null,

                            'social_facebook' =>
                            $supplierData['website_data']['facebook'][0]
                                ?? null,

                            'social_instagram' =>
                            $supplierData['website_data']['instagram'][0]
                                ?? null,

                            'country' =>
                            isset($supplierData['location_country_code'])
                                ? strtolower(
                                    $supplierData['location_country_code']
                                )
                                : null,

                            'zip' =>
                            $supplierData['location_postal_code']
                                ?? null,

                            'street' =>
                            $supplierData['location_street_1']
                                ?? null,

                            'lon' =>
                            $supplierData['location_longitude']
                                ?? null,

                            'lat' =>
                            $supplierData['location_latitude']
                                ?? null,

                            'daysoff' => json_encode($daysOff),

                        ]
                    );

                    /*
                |--------------------------------------------------------------------------
                | Remove Old Categories
                |--------------------------------------------------------------------------
                */

                    SupplierCategoryRelationship::where(
                        'supplier_id',
                        $supplier->id
                    )->delete();

                    /*
                |--------------------------------------------------------------------------
                | Save Categories
                |--------------------------------------------------------------------------
                */

                    foreach (
                        $supplierData['types'] ?? []
                        as $type
                    ) {

                        $category = ScrapCategory::firstOrCreate(

                            [
                                'scraper_category_id' => $type['type']
                            ],

                            [
                                'name' => ucwords(
                                    str_replace('-', ' ', $type['type'])
                                ),

                                'description' =>
                                ucwords(
                                    str_replace('-', ' ', $type['type'])
                                ) . ' category imported from Scrap API',

                                'active' => 1
                            ]
                        );
                        SupplierCategoryRelationship::create([

                            'supplier_id' =>
                            $supplier->id,

                            'category_id' =>
                            $category->id,

                            'is_main' =>
                            $type['is_main']
                                ?? false,
                        ]);
                    }
                });
            }
        } while (!empty($cursor));

        /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->back()
            ->with('success', 'Suppliers Imported Successfully');
    }
}
