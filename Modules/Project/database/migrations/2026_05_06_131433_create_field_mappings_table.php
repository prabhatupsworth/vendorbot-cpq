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
        Schema::create('field_mappings', function (Blueprint $table) {

            $table->id();

            // 🔗 Project relation
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 Pipedrive field key
            $table->string('pipedrive_field_key');

            // 🔥 System field
            $table->string('system_field');

            $table->timestamps();

            $table->softDeletes();

            // ✅ Prevent duplicate mapping
            $table->unique([
                'project_id',
                'pipedrive_field_key',
                'system_field'
            ], 'field_mapping_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_mappings');
    }
};
