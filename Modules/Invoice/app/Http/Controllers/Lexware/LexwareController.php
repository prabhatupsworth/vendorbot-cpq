<?php

namespace Modules\Invoice\Http\Controllers\Lexware;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

use Illuminate\Http\Request;
use Modules\Invoice\Models\InvoiceAccount;
use App\Traits\ActivityLogTrait;


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
            'api_key' => 'required|string',
            'base_url' => 'required|url',
            'currency' => 'nullable|string|max:3',
        ]);

        $invoiceAccount = InvoiceAccount::create([
            'type' => $request->type,
            'api_key' => $request->api_key,
            'base_url' => $request->base_url,
            'currency' => $request->currency,
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
            'api_key' => 'nullable|string',
            'base_url' => 'required|url',
            'currency' => 'nullable|string|max:3',
        ]);

        $account = InvoiceAccount::findOrFail($id);

        $account->update([
            'type' => $request->type,
            'api_key' => $request->api_key,
            'base_url' => $request->base_url,
            'currency' => $request->currency,
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

    public function connect(int $id)
    {
        // Logic to connect to Lexware using the provided ID
        // This could involve redirecting to an OAuth flow or similar

        // update invoice account to mark as verified after successful connection
        $account = InvoiceAccount::findOrFail($id);
        $account->is_verified = true;
        $account->save();
        $this->activityLog([
            'module' => 'lexware',
            'action' => 'connected',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Connected to Lexware successfully.',
        ]);
        return redirect()->route('invoice::lexware.index')->with('success', 'Connected to Lexware successfully.');
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
}
