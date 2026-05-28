<?php

namespace Modules\Product\Services;

use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Services\PipedriveService;
use Modules\Product\Models\Product;

class ProductService
{
    public function create(array $data): Product
    {
        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */

        $product = Product::create($data);

        /*
        |--------------------------------------------------------------------------
        | Sync Pipedrive
        |--------------------------------------------------------------------------
        */

        $account = PipedriveAccount::first();

        if ($account && $account->is_verified) {

            $pipedriveService = new PipedriveService(
                baseUrl: $account->base_url,
                apiKey: $account->api_key
            );

            $pipedriveService
                ->createProduct($product);
        }

        return $product;
    }
}
