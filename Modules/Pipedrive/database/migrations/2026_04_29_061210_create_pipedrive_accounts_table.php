<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pipedrive_accounts', function (Blueprint $table) {
            $table->id();

            $table->uuid('u_id')->unique();

            $table->string('api_key');
            $table->string('base_url');
            $table->string('account_name');

            $table->boolean('is_verified')->default(false);

            // 🔥 NEW FLAGS
            $table->boolean('sync_stages')->default(false);
            $table->boolean('sync_fields')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // ⚡ Indexes
            $table->index('api_key');
            $table->index('u_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipedrive_accounts');
    }
};
