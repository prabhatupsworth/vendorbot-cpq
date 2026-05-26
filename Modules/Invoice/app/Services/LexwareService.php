<?php

namespace Modules\Invoice\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\InvoiceAccount;

class LexwareService
{
    protected string $baseUrl;
    protected string $apiToken;

    public function __construct(protected InvoiceAccount $account)
    {
        $this->baseUrl = rtrim(
            $account->base_url,
            '/'
        );

        $this->apiToken = $account->api_key;
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function testConnection(): array
    {
        try {

            $response = Http::withHeaders(
                $this->headers()
            )->get(
                $this->baseUrl . '/profile'
            );

            if ($response->successful()) {

                return [
                    'success' => true,
                    'message' => 'Lexware connected successfully.',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Connection failed.',
                'status' => $response->status(),
                'error' => $response->json(),
            ];
        } catch (\Throwable $e) {

            Log::error('Lexware Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function createInvoice(array $payload): array
    {
        try {

            $response = Http::withHeaders(
                $this->headers()
            )->post(
                $this->baseUrl . '/invoices',
                $payload
            );

            if ($response->successful()) {

                return [
                    'success' => true,
                    'message' => 'Invoice created successfully.',
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Invoice creation failed.',
                'status'  => $response->status(),
                'error'   => $response->json(),
            ];
        } catch (\Throwable $e) {

            Log::error('Lexware Create Invoice Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve Invoice
     */
    public function getInvoice(string $invoiceId): array
    {
        try {

            $response = Http::withHeaders(
                $this->headers()
            )->get(
                $this->baseUrl . '/invoices/' . $invoiceId
            );

            if ($response->successful()) {

                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Invoice not found.',
                'status'  => $response->status(),
                'error'   => $response->json(),
            ];
        } catch (\Throwable $e) {

            Log::error('Lexware Get Invoice Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Download Invoice File
     */
    public function downloadInvoice(string $invoiceId)
    {
        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Accept'        => 'application/pdf',
            ])->get(
                $this->baseUrl . '/invoices/' . $invoiceId . '/document'
            );

            if (! $response->successful()) {

                return [
                    'success' => false,
                    'message' => 'Invoice download failed.',
                    'status'  => $response->status(),
                ];
            }

            return response(
                $response->body(),
                200,
                [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="invoice-' . $invoiceId . '.pdf"',
                ]
            );
        } catch (\Throwable $e) {

            Log::error('Lexware Download Invoice Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
