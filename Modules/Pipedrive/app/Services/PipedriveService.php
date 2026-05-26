<?php

namespace Modules\Pipedrive\Services;

use Illuminate\Support\Facades\Http;
use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Models\PipedriveStage;

class PipedriveService
{
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct(
        string $baseUrl,
        string $apiKey
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');

        $this->apiKey = $apiKey;
    }

    /*
    |--------------------------------------------------------------------------
    | Reusable Request
    |--------------------------------------------------------------------------
    */

    protected function request(
        string $method,
        string $endpoint,
        array $data = []
    ): array {

        $response = Http::timeout(60)
            ->acceptJson()
            ->{$method}(
                "{$this->baseUrl}/api/v1/{$endpoint}",
                array_merge(
                    $data,
                    [
                        'api_token' => $this->apiKey,
                    ]
                )
            );

        if (!$response->successful()) {

            return [
                'status' => false,
                'message' => 'Pipedrive API Request Failed',
                'response' => $response->json(),
            ];
        }

        return [
            'status' => true,
            'data' => $response->json('data'),
            'additional_data' => $response->json(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Test Connection
    |--------------------------------------------------------------------------
    */

    public function testConnection(): array
    {
        return $this->request(
            'get',
            'users/me'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Fields
    |--------------------------------------------------------------------------
    */

    public function syncFields(
        PipedriveAccount $account
    ): array {

        $response = $this->request(
            'get',
            'dealFields'
        );

        if (!$response['status']) {

            return $response;
        }

        $fields = $response['data'] ?? [];

        foreach ($fields as $field) {

            \Modules\Pipedrive\Models\PipedriveField::updateOrCreate(

                [
                    'pipedrive_account_id' => $account->id,
                    'field_key' => $field['key'],
                ],

                [
                    'name' => $field['name'],

                    'field_type' => $field['field_type'] ?? null,

                    'options' => !empty($field['options'])
                        ? json_encode($field['options'])
                        : null,

                    'subfields' => !empty($field['subfields'])
                        ? json_encode($field['subfields'])
                        : null,

                    'is_custom_field' => $field['custom'] ?? false,
                ]
            );
        }

        return [
            'status' => true,
            'message' => 'Fields synced successfully',
            'total_fields' => count($fields),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Stages
    |--------------------------------------------------------------------------
    */

    public function syncStages(PipedriveAccount $account): array
    {

        $response = $this->request(
            'get',
            'stages'
        );

        if (!$response['status']) {

            return $response;
        }

        $stages = $response['data'] ?? [];

        foreach ($stages as $stage) {

            PipedriveStage::updateOrCreate(

                [
                    'pipedrive_account_id' => $account->id,

                    'stage_id' => $stage['id'],
                ],

                [
                    'name' => $stage['name'],

                    'pipeline_id' => $stage['pipeline_id'],
                ]
            );
        }

        return [
            'status' => true,
            'message' => 'Stages synced successfully',
            'total_stages' => count($stages),
        ];
    }
}
