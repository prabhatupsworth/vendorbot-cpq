<?php

namespace Modules\Invoice\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\InvoiceAccount;

class LexwareService
{
    protected string $baseUrl;
    protected string $apiToken;

    public function __construct(
        protected InvoiceAccount $account
    ) {
        $this->baseUrl = rtrim(
            $account->base_url,
            '/'
        );

        $this->apiToken = $account->api_key;
    }

    /**
     * Default Headers
     */
    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Reusable Request Method
     */
    protected function request(
        string $method,
        string $endpoint,
        array $data = []
    ): array {

        try {

            $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

            $request = Http::withHeaders(
                $this->headers()
            )
                ->acceptJson()
                ->timeout(60);

            $response = match (strtolower($method)) {
                'get'    => $request->get($url, $data),
                'post'   => $request->post($url, $data),
                'put'    => $request->put($url, $data),
                'patch'  => $request->patch($url, $data),
                'delete' => $request->delete($url, $data),
                default  => throw new \Exception(
                    "Unsupported HTTP method: {$method}"
                ),
            };

            $json = $response->json();

            if (!$response->successful()) {

                Log::warning('Lexware API Error', [
                    'url'      => $url,
                    'status'   => $response->status(),
                    'response' => $json,
                ]);

                return [
                    'success' => false,
                    'status'  => $response->status(),
                    'message' => match ($response->status()) {
                        400 => 'Bad request.',
                        401 => 'Invalid API token.',
                        403 => 'Access denied.',
                        404 => 'Invalid API URL.',
                        422 => 'Validation failed.',
                        429 => 'Rate limit exceeded.',
                        500 => 'Lexware server error.',
                        default => $json['message']
                            ?? $json['error']
                            ?? 'Lexware API request failed.',
                    }
                ];
            }

            return [
                'success' => true,
                'data' => $json,
            ];

        } catch (ConnectionException $e) {

            Log::error('Lexware Connection Error', [
                'url'     => $url ?? null,
                'message' => $e->getMessage(),
            ]);

            if (str_contains(
                strtolower($e->getMessage()),
                'could not resolve host'
            )) {
                return [
                    'success' => false,
                    'message' => 'Invalid Base URL.',
                ];
            }

            if (str_contains(
                strtolower($e->getMessage()),
                'timed out'
            )) {
                return [
                    'success' => false,
                    'message' => 'Connection timeout.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Unable to connect to Lexware server.',
            ];

        } catch (\Throwable $e) {

            Log::error('Lexware Request Exception', [
                'url'     => $url ?? null,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => config('app.debug')
                    ? $e->getMessage()
                    : 'Something went wrong while connecting to Lexware.',
            ];
        }
    }

    /**
     * Test Connection
     */
    public function testConnection(): array
    {
        $response = $this->request(
            'get',
            'v1/profile'
        );

        if (!$response['success']) {
            return $response;
        }

        return [
            'success' => true,
            'message' => 'Lexware connected successfully.',
            'data'    => $response['data'],
        ];
    }
}
