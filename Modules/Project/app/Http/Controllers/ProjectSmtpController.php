<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Traits\ActivityLogTrait;

use Modules\Project\Services\SmtpService;

use Modules\Project\Interfaces\SmtpRepositoryInterface;

use Modules\Project\Http\Requests\SMTP\TestSmtpRequest;
use Modules\Project\Http\Requests\SMTP\StoreSmtpRequest;
use Modules\Project\Http\Requests\SMTP\UpdateSmtpRequest;

class ProjectSmtpController extends Controller
{
    use ActivityLogTrait;

    public function __construct(

        protected SmtpRepositoryInterface $smtpRepository,

        protected SmtpService $smtpService

    ) {}

    /**
     * List SMTPs
     */
    public function index(int $projectId)
    {
        return response()->json([

            'status' => true,

            'data' => $this->smtpRepository
                ->getAll($projectId)

        ]);
    }

    /**
     * Store SMTP
     */
    public function store(
        StoreSmtpRequest $request,
        int $projectId
    ) {

        $smtp = $this->smtpService
            ->store(
                $projectId,
                $request->validated()
            );

        $this->activityLog([

            'module' => 'projects',

            'record_id' => $projectId,

            'action' => 'create',

            'status' => 'success',

            'message' => 'SMTP created successfully',

        ]);

        return response()->json([

            'status' => true,

            'action' => 'append',

            'target' => '#smtp-section',

            'message' => 'SMTP created successfully',

            'html' => view(
                'project::partials.smtp-card',
                [
                    'smtp' => $smtp,
                    'projectId' => $projectId
                ]
            )->render(),

            'data' => $smtp

        ]);
    }

    /**
     * Show SMTP
     */
    public function show(
        int $projectId,
        int $smtp
    ) {

        return response()->json([

            'status' => true,

            'data' => $this->smtpRepository
                ->find(
                    $projectId,
                    $smtp
                )

        ]);
    }

    /**
     * Update SMTP
     */
    public function update(
        UpdateSmtpRequest $request,
        int $projectId,
        int $smtp
    ) {

        $data = $request->validated();

        $data['is_active'] = $request->has('is_active');

        $this->smtpService
            ->update(
                $projectId,
                $smtp,
                $data
            );

        $smtpData = $this->smtpRepository
            ->find(
                $projectId,
                $smtp
            );

        return response()->json([

            'status' => true,

            'action' => 'update',

            'target' => '.smtp-item',

            'id' => $smtp,

            'message' => 'SMTP updated successfully',

            'html' => view(
                'project::partials.smtp-card',
                [
                    'smtp' => $smtpData,
                    'projectId' => $projectId
                ]
            )->render(),

        ]);
    }

    /**
     * Delete SMTP
     */
    public function destroy(
        int $projectId,
        int $smtp
    ) {

        $this->smtpRepository
            ->delete(
                $projectId,
                $smtp
            );

        return response()->json([

            'status' => true,

            'action' => 'delete',

            'target' => '.smtp-item',

            'id' => $smtp,

            'message' => 'SMTP deleted successfully'

        ]);
    }

    /**
     * Test SMTP
     */
    public function testSmtp(
        TestSmtpRequest $request,
        int $projectId,
        int $smtp
    ) {

        $response = $this->smtpService
            ->test(
                $projectId,
                $smtp,
                $request->validated()
            );

        if (!$response['status']) {

            return response()->json(
                $response,
                500
            );
        }

        return response()->json([

            'status' => true,

            'action' => 'update',

            'target' => '.smtp-item',

            'id' => $smtp,

            'message' => $response['message'],

            'html' => view(
                'project::partials.smtp-card',
                [
                    'smtp' => $response['smtp'],
                    'projectId' => $projectId
                ]
            )->render(),

        ]);
    }
}
