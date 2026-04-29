<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // 🔥 WHO
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // 🔥 WHAT MODULE
            $table->string('module'); // pipedrive, order, user, auth

            // 🔥 WHICH RECORD
            $table->unsignedBigInteger('record_id')->nullable();

            // 🔥 ACTION
            $table->string('action'); // create, update, delete, connect

            // 🔥 STATUS
            $table->string('status')->nullable(); // success, failed

            // 🔥 MESSAGE
            $table->text('message')->nullable();

            // 🔥 EXTRA DATA
            $table->json('meta')->nullable();

            // 🔥 SYSTEM INFO
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('performed_at')->nullable();

            $table->timestamps();

            // ⚡ Indexing
            $table->index(['module', 'record_id']);
            $table->index('user_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
