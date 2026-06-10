<?php

namespace Modules\Pipedrive\Services;

use Modules\Product\Models\Product;
use Modules\Pipedrive\Models\PipedriveAccount;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function importProduct(int|string $crmProductId, array $scrapCategories = []): Product
    {

        $account = $this->getAccount();

        $response = Http::timeout(120)
            ->acceptJson()
            ->get(
                "https://api.pipedrive.com/v1/products/{$crmProductId}",
                [
                    'api_token' => $account->api_key,
                ]
            );

        if ($response->status() === 404) {
            throw new \Exception(
                'The selected product no longer exists in Pipedrive.'
            );
        }

        if (!$response->successful()) {
            throw new \Exception(
                'Failed to fetch product from Pipedrive.'
            );
        }

        $data = $response->json('data');

        if (!$data) {
            throw new \Exception(
                'Product not found in Pipedrive.'
            );
        }

        $product = Product::updateOrCreate(
            [
                'crm_product_id' => $data['id'],
            ],
            [
                'project_id'      => current_project_id(),
                'title'           => $data['name'],
                'product_code'    => $data['code'],
                'description'     => $data['description'] ?? null,
                'price'           => $data['prices'][0]['price'] ?? 0,
                'cost'            => $data['prices'][0]['cost'] ?? 0,
                'currency_code'   => $data['prices'][0]['currency'] ?? 'USD',
                'active'          => $data['active_flag'] ?? true,
                'is_sync_backend' => true,
            ]
        );

        $product->scrapCategories()->sync(
            $scrapCategories
        );

        return $product;
    }


    public function createProduct(Product $product): array
    {
        $account = $this->getAccount();

        $response = Http::timeout(120)
            ->acceptJson()
            ->post(
                "https://api.pipedrive.com/v1/products?api_token={$account->api_key}",
                [
                    'name' => $product->title,
                    'code' => $product->product_code,
                    'description' => $product->description,
                    'active_flag' => (bool) $product->active,

                    'prices' => [
                        [
                            'price' => (float) $product->price,
                            'cost' => (float) $product->cost,
                            'currency' => $product->currency_code,
                        ]
                    ],
                ]
            );

        if (!$response->successful()) {
            throw new \Exception(
                $response->json('error') ??
                    'Failed to create product in Pipedrive.'
            );
        }

        $data = $response->json('data');

        $product->update([
            'crm_product_id' => $data['id'],
            'is_sync_backend' => true,
        ]);

        return $data;
    }

    public function updateProduct(Product $product): array
    {
        if (!$product->crm_product_id) {
            throw new \Exception(
                'CRM Product ID not found.'
            );
        }

        $account = $this->getAccount();

        $response = Http::timeout(120)
            ->acceptJson()
            ->put(
                "https://api.pipedrive.com/v1/products/{$product->crm_product_id}?api_token={$account->api_key}",
                [
                    'name' => $product->title,
                    'code' => $product->product_code,
                    'description' => $product->description,
                    'active_flag' => (bool) $product->active,

                    'prices' => [
                        [
                            'price' => (float) $product->price,
                            'cost' => (float) $product->cost,
                            'currency' => $product->currency_code,
                        ]
                    ],
                ]
            );

        if (!$response->successful()) {
            throw new \Exception(
                $response->json('error') ??
                    'Failed to update product in Pipedrive.'
            );
        }

        $product->update([
            'is_sync_backend' => true,
        ]);

        return $response->json('data');
    }

    protected function getAccount(): PipedriveAccount
    {
        $account = PipedriveAccount::where(
            'is_verified',
            true
        )
            ->whereIn(
                'id',
                function ($query) {
                    $query->select('pipedrive_account_id')
                        ->from('projects')
                        ->where(
                            'id',
                            current_project_id()
                        );
                }
            )
            ->first();

        if (!$account) {
            throw new \Exception(
                'Please connect and verify a Pipedrive account first.'
            );
        }

        return $account;
    }

    public function syncProduct(Product $product): array
    {
        if ($product->crm_product_id) {
            return $this->updateProduct($product);
        }

        return $this->createProduct($product);
    }
}
