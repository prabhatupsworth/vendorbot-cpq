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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Project
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // CRM
            $table->string('crm_product_id')->nullable();

            // Product Info
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('product_code')->nullable();

            // Pricing
            $table->decimal('cost', 15, 2)->nullable();
            $table->decimal('price', 15, 2);
            $table->string('currency_code', 10)->nullable();

            // Content
            $table->longText('description')->nullable();
            $table->longText('proposal_desc')->nullable();

            // Marketing
            $table->boolean('is_best_seller')->default(false);

            // Sync
            $table->boolean('is_sync_backend')->default(0);

            // Status
            $table->boolean('active')->default(1);

            $table->softDeletes();

            $table->timestamps();

            $table->index('project_id');
            $table->index('product_code');
            $table->index('active');
            $table->index('is_sync_backend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
