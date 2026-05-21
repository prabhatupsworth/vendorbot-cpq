<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scrap_categories', function (Blueprint $table) {

            $table->id();

            // External Scraper.io Category ID
            $table->string('scraper_category_id')
                ->unique();

            $table->string('name');

            $table->text('description')
                ->nullable();

            $table->boolean('active')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrap_categories');
    }
};
