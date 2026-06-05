<?php

namespace Modules\Project\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Project\Models\Smtp;

use Modules\Project\Repositories\SmtpRepository;

class SmtpService
{
    public function __construct(
        protected SmtpRepository $smtpRepository
    ) {}

    /**
     * Store SMTP
     */
    public function store(
        int $projectId,
        array $data
    ) {

        // if ($data['type'] === 'default') {

        //     $this->smtpRepository
        //         ->updateDefaultType($projectId);
        // }
       

        $data['project_id'] = $projectId;

        if(@$data['type'])
        {
            $smtpData = Smtp::where('project_id',$projectId)->where('type',$data['type'])->first();
            if(@$smtpData->id)
            {
                return 'type_already';
            }
        }
      

        return $this->smtpRepository
            ->store($data);
    }

    /**
     * Update SMTP
     */
    public function update(
        int $projectId,
        int $id,
        array $data
    ) {

        if (empty($data['password'])) {

            unset($data['password']);
        }

        if ($data['type'] === 'default') {

            $this->smtpRepository
                ->updateDefaultType(
                    $projectId,
                    $id
                );
        }

        return $this->smtpRepository
            ->update(
                $projectId,
                $id,
                $data
            );
    }

    /**
     * Test SMTP
     */
    public function test(
        int $projectId,
        int $smtpId,
        array $data
    ): array {

        $smtp = null;

        try {

            $smtp = $this->smtpRepository
                ->find(
                    $projectId,
                    $smtpId
                );

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

            if (!empty($data['html'])) {

                Mail::html(
                    $data['html'],
                    function ($mail) use ($data) {
                        $mail->to($data['to_email'])
                            ->subject($data['subject']);
                    }
                );
            } else {

                Mail::raw(
                    $data['message'],
                    function ($mail) use ($data) {
                        $mail->to($data['to_email'])
                            ->subject($data['subject']);
                    }
                );
            }

            $this->smtpRepository
                ->updateConnectionStatus(
                    $projectId,
                    $smtpId,
                    true
                );

            return [

                'status' => true,

                'smtp' => $smtp,

                'message' => 'SMTP is configured correctly and the email was sent successfully.'

            ];
        } catch (\Exception $e) {

            Log::error(
                'SMTP Test Error: '
                    . $e->getMessage()
            );

            if ($smtp) {

                $this->smtpRepository
                    ->updateConnectionStatus(
                        $projectId,
                        $smtpId,
                        false
                    );
            }

            return [

                'status' => false,

                'message' => $e->getMessage()

            ];
        }
    }


    public function sendDraft(
        int $projectId,
        int $smtpId,
        string $toEmail,
        object $draft
    ): array {

        try {

            $smtp = $this->smtpRepository->find(
                $projectId,
                $smtpId
            );

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

            Mail::html(
                $draft->content,
                function ($mail) use ($toEmail, $draft) {

                    $mail->to($toEmail)
                        ->subject($draft->subject);
                }
            );

            return [
                'status' => true,
                'message' => 'Draft sent successfully'
            ];
        } catch (\Exception $e) {

            Log::error(
                'Draft Send Error: '
                    . $e->getMessage()
            );

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
