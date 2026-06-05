<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_category_relationship', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('scrap_categories')
                ->cascadeOnDelete();

            $table->boolean('is_main')
                ->default(false);

            $table->timestamps();

            // Prevent duplicate category assignment
            $table->unique(
                [
                    'project_id',
                    'supplier_id',
                    'category_id'
                ],
                'psc_unique'
            );

            // Performance indexes
            $table->index(
                ['project_id', 'supplier_id'],
                'proj_supplier_idx'
            );

            $table->index(
                ['project_id', 'category_id'],
                'proj_category_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'supplier_category_relationship'
        );
    }
};
