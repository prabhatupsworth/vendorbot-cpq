<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_category_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('draft_category_id')
                ->constrained('draft_categories')
                ->cascadeOnDelete();

            $table->string('locale', 10);
            $table->string('name');

            $table->timestamps();

            $table->unique(['draft_category_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_category_translations');
    }
};
