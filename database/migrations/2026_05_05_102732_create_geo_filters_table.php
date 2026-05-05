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
        Schema::create('geo_filters', function (Blueprint $table) {
            $table->id();

            // 🔗 Project Relation (required)
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🌍 Geo ranges
            $table->decimal('latitude_range', 10, 6)->default(0.03);
            $table->decimal('longitude_range', 10, 6)->default(0.03);

            // optional status
            $table->boolean('status')->default(true);

            $table->timestamps();

            // 🔥 Important: one geo filter per project
            $table->unique('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_filters');
    }
};
