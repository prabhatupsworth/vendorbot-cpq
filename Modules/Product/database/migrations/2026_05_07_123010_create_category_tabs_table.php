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
        Schema::create('category_tabs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('name');

            $table->boolean('is_default')
                ->default(false);

            $table->integer('sort_order')
                ->default(0);

            $table->boolean('active')
                ->default(true);

            $table->softDeletes();

            // Indexes
            $table->index(['category_id', 'active']);

            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_tabs');
    }
};
