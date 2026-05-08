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
        Schema::create('categories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->string('name');

            $table->text('description')->nullable();

            $table->enum('selection_type', ['single', 'multiple'])
                ->default('single');

            $table->boolean('is_required')
                ->default(false);

            $table->boolean('has_tabs')
                ->default(false);

            $table->boolean('has_default')
                ->default(false);

            $table->integer('sort_order')
                ->default(0);

            $table->boolean('active')
                ->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index(['project_id', 'active']);

            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
