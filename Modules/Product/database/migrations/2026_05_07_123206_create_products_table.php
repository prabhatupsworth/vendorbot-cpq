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

            // Relations
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            // Product Info
            $table->string('name');

            $table->text('description')->nullable();

            $table->text('pdf_description')->nullable();

            // Pricing
            $table->decimal('price', 12, 2)->default(0);

            $table->decimal('cost', 12, 2)->default(0);

            $table->enum('discount_type', ['fixed', 'percent'])
                ->nullable();

            $table->decimal('discount_value', 12, 2)
                ->default(0);

            // External System
            $table->string('pipedrive_product_id')
                ->nullable()
                ->index();

            // Flags
            $table->boolean('is_default')
                ->default(false);

            $table->boolean('is_pro')
                ->default(false);

            $table->boolean('show_only')
                ->default(false);

            $table->boolean('active')
                ->default(true);

            $table->boolean('is_sync_backend')
                ->default(false);

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index(['project_id']);

            $table->index(['active', 'is_default']);

            $table->index('name');
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
