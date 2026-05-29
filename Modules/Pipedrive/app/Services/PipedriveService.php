<?php

namespace Modules\Pipedrive\Services;

use Illuminate\Support\Facades\Http;
use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Models\PipedriveStage;
use Modules\Product\Models\Product;

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
                "{$this->baseUrl}/api/v2/{$endpoint}",
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

    public function syncFields(PipedriveAccount $account): array
    {
        $endpoints = [
            'dealFields' => 'deal',
            'personFields' => 'person',
            'organizationFields' => 'organization',
            'productFields' => 'product',
        ];

        $totalFields = 0;

        foreach ($endpoints as $endpoint => $entityType) {

            $response = $this->request('get', $endpoint);

            if (!$response['status']) {
                continue;
            }

            $fields = $response['data'] ?? [];

            foreach ($fields as $field) {

                if (!($field['is_custom_field'] ?? false)) {
                    continue;
                }

                \Modules\Pipedrive\Models\PipedriveField::updateOrCreate(
                    [
                        'pipedrive_account_id' => $account->id,
                        'field_key' => $field['field_code'],
                        'entity_type' => $entityType,
                    ],
                    [
                        'name' => $field['field_name'],
                        'field_type' => $field['field_type'] ?? null,
                        'is_custom_field' => true,
                    ]
                );

                $totalFields++;
            }
        }

        return [
            'status' => true,
            'message' => 'Custom fields synced successfully',
            'total_fields' => $totalFields,
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



    /*
    |--------------------------------------------------------------------------
    | Create Product
    |--------------------------------------------------------------------------
    */

    public function createProduct(Product $product)
    {

        $payload = [

            'name' => $product->name,

            'code' => $product->id,

            'description' => $product->description,

            'active_flag' => (bool) $product->active,

            'prices' => [

                [
                    'price' => $product->price,
                    'currency' => 'INR',
                ]
            ]
        ];

        $response = $this->request(
            'post',
            'products',
            $payload
        );

        if (!$response['status']) {

            return $response;
        }
    }
}
