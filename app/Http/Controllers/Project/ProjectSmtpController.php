<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use App\Enums\SmtpType;
use Illuminate\Support\Facades\Mail;

class ProjectSmtpController extends Controller
{
    /**
     * 🔹 Get all SMTPs for project
     */
    public function index(int $projectId)
    {
        $smtps = Smtp::where('project_id', $projectId)->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $smtps
        ]);
    }

    /**
     * 🔹 Store SMTP (create)
     */
    public function store(Request $request, int $projectId)
    {
        $validated = $request->validate([
            'type' => ['required', new Enum(SmtpType::class)],
            'host' => 'required|string',
            'port' => 'nullable|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);

        try {

            // 🔥 ensure only one default per project
            if ($validated['type'] === 'default') {
                Smtp::where('project_id', $projectId)
                    ->where('type', 'default')
                    ->update(['type' => 'customer']); // fallback
            }

            $smtp = Smtp::create([
                ...$validated,
                'project_id' => $projectId
            ]);
            return response()->json([
                'status' => true,
                'action' => 'append',
                'target' => '#smtp-section',
                'message' => 'SMTP created successfully',
                'html' => view('projects.partials.smtp-card', [
                    'smtp' => $smtp,
                    'projectId' => $projectId
                ])->render(),
                'data' => $smtp
            ]);
        } catch (\Exception $e) {

            Log::error('SMTP Store Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create SMTP'
            ], 500);
        }
    }

    /**
     * 🔹 Show single SMTP (edit)
     */
    public function show(int $projectId, int $id)
    {
        $smtp = Smtp::where('project_id', $projectId)
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $smtp
        ]);
    }

    /**
     * 🔹 Update SMTP
     */
    public function update(Request $request, int $projectId, int $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:default,customer,invoice,supplier',
            'host' => 'required|string',
            'port' => 'nullable|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);

        try {

            $smtp = Smtp::where('project_id', $projectId)
                ->findOrFail($id);

            // ✅ handle checkbox (active/inactive)
            $validated['is_active'] = $request->has('is_active');

            // ✅ prevent empty password overwrite
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                // 🔐 optional: encrypt password
                // $validated['password'] = encrypt($validated['password']);
            }

            // 🔥 handle default (only one allowed)
            if ($validated['type'] === 'default') {
                Smtp::where('project_id', $projectId)
                    ->where('type', 'default')
                    ->where('id', '!=', $id)
                    ->update(['type' => 'customer']);
            }

            $smtp->update($validated);

            return response()->json([
                'status' => true,
                'action' => 'update',
                'target' => '.smtp-item',
                'id' => $smtp->id,
                'message' => 'SMTP updated successfully',
                'html' => view('projects.partials.smtp-card', [
                    'smtp' => $smtp,
                    'projectId' => $projectId
                ])->render(),
            ]);
        } catch (\Exception $e) {

            Log::error('SMTP Update Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update SMTP'
            ], 500);
        }
    }

    /**
     * 🔹 Delete SMTP
     */
    public function destroy(int $projectId, int $id)
    {
        try {

            $smtp = Smtp::where('project_id', $projectId)
                ->findOrFail($id);

            $smtp->delete();

            return response()->json([
                'status' => true,
                'action' => 'delete',
                'target' => '.smtp-item',
                'id'     => $id,
                'message' => 'SMTP deleted successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('SMTP Delete Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete SMTP'
            ], 500);
        }
    }



    public function testSmtp(Request $request, int $projectId, int $id)
    {
        // ✅ validate form
        $validated = $request->validate([
            'to_email' => ['required', 'email'],
            'subject'  => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string'],
        ]);

        $smtp = null;

        try {

            // 🔥 get smtp
            $smtp = Smtp::where('project_id', $projectId)
                ->findOrFail($id);

            // 🔥 dynamic smtp config
            config([

                'mail.default' => 'smtp',

                'mail.mailers.smtp.transport' => 'smtp',

                'mail.mailers.smtp.host' => $smtp->host,

                'mail.mailers.smtp.port' => $smtp->port,

                'mail.mailers.smtp.username' => $smtp->username,

                'mail.mailers.smtp.password' => $smtp->password,

                'mail.mailers.smtp.encryption' => $smtp->encryption,

                'mail.from.address' => $smtp->from_email,

                'mail.from.name' => $smtp->from_name,
            ]);

            // 🔥 send test mail
            Mail::raw($validated['message'], function ($mail) use ($validated) {

                $mail->to($validated['to_email'])
                    ->subject($validated['subject']);
            });

            // ✅ connected success
            $smtp->update([
                'connected' => true,
            ]);

            return response()->json([
                'status' => true,
                'action' => 'update',
                'target' => '.smtp-item',
                'id' => $smtp->id,
                'message' => 'SMTP test mail sent successfully',
                'html' => view('projects.partials.smtp-card', [
                    'smtp' => $smtp,
                    'projectId' => $projectId
                ])->render(),
            ]);

        } catch (\Exception $e) {

            // ✅ safe update
            if ($smtp) {

                $smtp->update([
                    'connected' => false,
                ]);
            }

            Log::error('SMTP Test Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
