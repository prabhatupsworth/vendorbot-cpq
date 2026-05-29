<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('draft_category_id')
                ->constrained('draft_categories')
                ->cascadeOnDelete();

            $table->string('subject');

            $table->longText('content')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('draft_category_id');
            $table->index(['project_id', 'draft_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
