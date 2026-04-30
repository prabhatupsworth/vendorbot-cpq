<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('uid')->unique();
            $table->string('name');
            $table->string('slug')->unique();

            $table->string('website_url')->nullable();
            $table->string('event_name')->nullable();

            $table->enum('flow_type', ['simple', 'full'])->default('simple');

            $table->boolean('invoice_enabled')->default(false);

            // Relations
            $table->foreignId('pipedrive_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invoice_account_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('pipedrive_sync_status')->default(false);

            $table->boolean('plugin_connected')->default(false);
            $table->dateTime('plugin_connected_at')->nullable();
            $table->dateTime('plugin_last_ping_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
