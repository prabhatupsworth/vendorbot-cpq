<?php

namespace Modules\Pipedrive\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Models\PipedriveStage;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Log;
use Modules\Pipedrive\Models\PipedriveField;

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
        try {

            $response = Http::timeout(60)
                ->acceptJson()
                ->{$method}(
                    "{$this->baseUrl}/api/{$endpoint}",
                    array_merge($data, [
                        'api_token' => $this->apiKey,
                    ])
                );

            $json = $response->json();

            // HTTP Errors
            if (!$response->successful()) {

                return [
                    'status' => false,
                    'message' => match ($response->status()) {
                        401 => 'Invalid API token.',
                        403 => 'Access denied.',
                        404 => 'API endpoint not found.',
                        429 => 'Rate limit exceeded.',
                        default => $json['error']
                            ?? $json['message']
                            ?? 'API request failed.',
                    }
                ];
            }

            // Pipedrive-style API error in 200 response
            if (
                isset($json['success']) &&
                $json['success'] === false
            ) {
                return [
                    'status' => false,
                    'message' => $json['error']
                        ?? $json['message']
                        ?? 'Invalid API token.',
                ];
            }

            return [
                'status' => true,
                'data' => $json['data'] ?? [],
                'additional_data' => $json,
            ];
        } catch (ConnectionException $e) {

            $message = $e->getMessage();

            if (str_contains($message, 'Could not resolve host')) {
                return [
                    'status' => false,
                    'message' => 'Invalid Base URL.',
                ];
            }

            if (str_contains($message, 'timed out')) {
                return [
                    'status' => false,
                    'message' => 'Connection timeout.',
                ];
            }

            return [
                'status' => false,
                'message' => 'Unable to connect to server.',
            ];
        } catch (\Throwable $e) {

            Log::error('API Request Error', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => 'Something went wrong.',
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Test Connection
    |--------------------------------------------------------------------------
    */
    public function testConnection(): array
    {
        try {

            $response = $this->request(
                'get',
                'v1/users/me'
            );

            if (!$response['status']) {
                return [
                    'status'  => false,
                    'message' => $response['message'],
                ];
            }

            return [
                'status'  => true,
                'message' => 'Pipedrive connection successful.',
                'user'    => $response['data'] ?? null,
            ];
        } catch (\Throwable $e) {

            Log::error('Pipedrive connection test failed', [
                'message' => $e->getMessage(),
            ]);

            return [
                'status'  => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Sync Fields
    |--------------------------------------------------------------------------
    */

    public function syncFields(PipedriveAccount $account): array
    {
        try {

            $endpoints = [
                'dealFields'         => 'deal',
                'personFields'       => 'person',
                'organizationFields' => 'organization',
                'productFields'      => 'product',
            ];

            $totalFields = 0;

            foreach ($endpoints as $endpoint => $entityType) {

                $response = $this->request(
                    'get',
                    'v2/' . $endpoint
                );

                // ❌ Stop immediately if API failed
                if (!$response['status']) {

                    Log::warning('Pipedrive field sync failed', [
                        'account_id' => $account->id,
                        'endpoint'   => $endpoint,
                        'message'    => $response['message'] ?? 'Unknown error',
                    ]);

                    return [
                        'status' => false,
                        'message' => $response['message'] ?? 'Field sync failed',
                    ];
                }

                $fields = $response['data'] ?? [];

                foreach ($fields as $field) {

                    if (!($field['is_custom_field'] ?? false)) {
                        continue;
                    }

                    PipedriveField::updateOrCreate(
                        [
                            'pipedrive_account_id' => $account->id,
                            'field_key'            => $field['field_code'],
                            'entity_type'          => $entityType,
                        ],
                        [
                            'name'            => $field['field_name'],
                            'field_type'      => $field['field_type'] ?? null,
                            'is_custom_field' => true,
                        ]
                    );

                    $totalFields++;
                }
            }

            return [
                'status'       => true,
                'message'      => 'Custom fields synced successfully',
                'total_fields' => $totalFields,
            ];
        } catch (\Throwable $e) {

            Log::error('Pipedrive field sync exception', [
                'account_id' => $account->id,
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
            ]);

            return [
                'status'       => false,
                'message'      => $e->getMessage(),
                'total_fields' => 0,
            ];
        }
    }

    public function syncStages(PipedriveAccount $account): array
    {
        try {

            // ==========================
            // Pipelines
            // ==========================

            $pipelineResponse = $this->request(
                'get',
                'v2/pipelines'
            );

            if (!$pipelineResponse['status']) {
                return $pipelineResponse;
            }

            $pipelines = $pipelineResponse['data'] ?? [];

            foreach ($pipelines as $pipeline) {

                \Modules\Pipedrive\Models\PipedrivePipeline::updateOrCreate(
                    [
                        'pipedrive_account_id' => $account->id,
                        'pipeline_id' => $pipeline['id'],
                    ],
                    [
                        'name' => $pipeline['name'],
                    ]
                );
            }

            // ==========================
            // Stages
            // ==========================

            $stageResponse = $this->request(
                'get',
                'v1/stages'
            );

            if (!$stageResponse['status']) {
                return $stageResponse;
            }

            $stages = $stageResponse['data'] ?? [];

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
                'message' => 'Pipelines & Stages synced successfully',
                'total_pipelines' => count($pipelines),
                'total_stages' => count($stages),
            ];
        } catch (\Throwable $e) {

            Log::error('Pipedrive stage sync failed', [
                'account_id' => $account->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
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
                    'currency' => current_project_id(),
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
