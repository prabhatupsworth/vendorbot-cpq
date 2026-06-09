<?php

namespace Modules\Invoice\Http\Controllers\Lexware;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

use Illuminate\Http\Request;
use Modules\Invoice\Models\InvoiceAccount;
use App\Traits\ActivityLogTrait;
use Modules\Invoice\Services\LexwareService;

class LexwareController extends Controller
{
    use ActivityLogTrait;
    public function index()
    {
        $settings = InvoiceAccount::where('type', 'lexware')->get();
        return view('invoice::lexware.index', compact('settings'));
    }

    public function edit(int $id)
    {
        $setting = InvoiceAccount::findOrFail($id);
        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lexware,manual,other',
            'account_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'base_url' => 'required|url',
        ]);

        $invoiceAccount = InvoiceAccount::create([
            'type' => $request->type,
            'account_name' => $request->account_name,
            'api_key' => $request->api_key,
            'base_url' => $request->base_url,
        ]);

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'created',
            'record_id' => $invoiceAccount->id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account created successfully.',
        ]);

        return redirect()->back()->with('success', 'Account added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'type' => 'required|in:lexware,manual,other',
            'account_name' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'base_url' => 'required|url',
        ]);

        $account = InvoiceAccount::findOrFail($id);

        $account->update([
            'type' => $request->type,
            'account_name' => $request->account_name,
            'api_key' => $request->api_key,
            'base_url' => $request->base_url,
        ]);

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'updated',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account updated successfully.',
        ]);

        return redirect()->back()->with('success', 'Account updated successfully.');
    }
    // show details of the invoice account
    public function details(int $id)
    {
        $account = InvoiceAccount::findOrFail($id);
        $activityLog = ActivityLog::with('user:id,name')
            ->where('module', 'lexware')
            ->where('record_id', $id)
            ->latest() // or orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();
        return response()->json(['account' => $account, 'activityLog' => $activityLog]);
    }

    public function testConnection(int $id)
    {
        $account = InvoiceAccount::findOrFail($id);

        $lexwareService = new LexwareService($account);

        $response = $lexwareService->testConnection();

        if ($response['success']) {

            $account->update([
                'is_verified' => true,
            ]);

            $this->activityLog([
                'module' => 'lexware',
                'action' => 'connection_tested',
                'record_id' => $id,
                'performed_at' => now(),
                'status' => 'success',
                'message' => 'Lexware connection successful.',
            ]);

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Lexware connected successfully.'
                );
        }

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'connection_failed',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'failed',
            'message' => 'Lexware connection failed.',
        ]);

        return redirect()
            ->back()
            ->with(
                'error',
                $response['message'] ?? 'Connection failed.'
            );
    }

    public function createInvoice()
    {
        $account = InvoiceAccount::findOrFail(1);

        $lexwareService = new LexwareService($account);

        $payload = [

            "archived" => false,

            "voucherDate" => now()->toISOString(),

            "address" => [
                "name" => "Bike & Ride GmbH & Co. KG",
                "supplement" => "Gebäude 10",
                "street" => "Musterstraße 42",
                "city" => "Freiburg",
                "zip" => "79112",
                "countryCode" => "DE"
            ],

            "lineItems" => [

                [
                    "type" => "custom",
                    "name" => "Energieriegel Testpaket",
                    "quantity" => 1,
                    "unitName" => "Stück",

                    "unitPrice" => [
                        "currency" => "EUR",
                        "netAmount" => 5,
                        "taxRatePercentage" => 0
                    ],

                    "discountPercentage" => 0
                ],

                [
                    "type" => "text",
                    "name" => "Strukturieren Sie Ihre Belege durch Text-Elemente.",
                    "description" => "Das hilft beim Verständnis"
                ]
            ],

            "totalPrice" => [
                "currency" => "EUR"
            ],

            "taxConditions" => [
                "taxType" => "net"
            ],

            "paymentConditions" => [
                "paymentTermLabel" => "10 Tage - 3 %, 30 Tage netto",
                "paymentTermDuration" => 30,

                "paymentDiscountConditions" => [
                    "discountPercentage" => 3,
                    "discountRange" => 10
                ]
            ],

            "shippingConditions" => [
                "shippingDate" => now()->addDays(2)->toISOString(),
                "shippingType" => "delivery"
            ],

            "title" => "Rechnung",

            "introduction" => "Ihre bestellten Positionen stellen wir Ihnen hiermit in Rechnung",

            "remark" => "Vielen Dank für Ihren Einkauf"
        ];

        $response = $lexwareService->createInvoice($payload);


    }


    public function destroy(int $id)
    {
        $account = InvoiceAccount::findOrFail($id);
        $account->delete();

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'deleted',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account deleted successfully.',
        ]);

        return redirect()->back()->with('success', 'Account deleted successfully.');
    }
}
