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
        Schema::create('stage_actions', function (Blueprint $table) {

            $table->id();

            // 🔗 Project relation
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 Pipedrive stage id
            $table->string('stage_id');

            // 🔥 Action type
            $table->string('action_type');

            // 🔥 Action config
            $table->json('action_config')->nullable();

            $table->timestamps();

            $table->softDeletes();

            // ✅ Prevent duplicate stage action
            $table->unique([
                'project_id',
                'stage_id',
                'action_type'
            ], 'stage_action_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_actions');
    }
};
