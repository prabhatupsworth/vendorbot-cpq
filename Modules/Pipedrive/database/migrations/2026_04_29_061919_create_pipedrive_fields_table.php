<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pipedrive_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pipedrive_account_id')
                ->constrained('pipedrive_accounts')
                ->cascadeOnDelete();

            $table->string('field_key');
            $table->string('name');
            $table->string('field_type');

            $table->softDeletes();

            $table->index('pipedrive_account_id');
            $table->unique(['pipedrive_account_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipedrive_fields');
    }
};
