<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Product\Models\ScrapCategory;

class SyncScrapCategories extends Command
{
    /**
     * Command Signature
     */
    protected $signature = 'scrap:sync-categories';

    /**
     * Command Description
     */
    protected $description = 'Sync Scrap Categories From API';

    /**
     * Execute Command
     */
    public function handle(): void
    {
        $this->info(
            'Fetching scrap categories...'
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | API Request
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(120)
                ->acceptJson()
                ->get(
                    'https://scrap.io/api/autocomplete/gmap-types',
                    [
                        'locale' => 'en',
                    ]
                );

            if (!$response->successful()) {

                $this->error(
                    'Failed to fetch categories'
                );

                return;
            }

            $categories = $response->json();

            if (empty($categories)) {

                $this->warn(
                    'No categories found'
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Progress Bar
            |--------------------------------------------------------------------------
            */

            $bar = $this->output
                ->createProgressBar(
                    count($categories)
                );

            $bar->start();

            /*
            |--------------------------------------------------------------------------
            | Save Categories
            |--------------------------------------------------------------------------
            */

            foreach ($categories as $index => $category) {

                ScrapCategory::updateOrCreate(

                    [
                        'scraper_category_id' => $category['id']
                    ],

                    [
                        'name' =>
                            $category['text'],

                        'description' =>
                            $category['text'] .
                            ' category imported from Scrap API',

                        'active' => true,
                    ]
                );

                $bar->advance();
            }

            $bar->finish();

            $this->newLine(2);

            $this->info(
                'Scrap categories synced successfully'
            );

            $this->info(
                'Total Categories: ' .
                count($categories)
            );

        } catch (\Exception $e) {

            $this->error(
                $e->getMessage()
            );
        }
    }
}
