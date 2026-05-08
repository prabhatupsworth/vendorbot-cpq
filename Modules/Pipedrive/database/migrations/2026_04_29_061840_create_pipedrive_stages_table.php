<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pipedrive_stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pipedrive_account_id')
                ->constrained('pipedrive_accounts')
                ->cascadeOnDelete();

            $table->string('stage_id');
            $table->string('pipeline_id');
            $table->string('name');

            $table->softDeletes();

            $table->index(['pipedrive_account_id', 'pipeline_id']);
            $table->unique(['pipedrive_account_id', 'stage_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipedrive_stages');
    }
};
