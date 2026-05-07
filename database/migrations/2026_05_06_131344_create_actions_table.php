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
        Schema::create('actions', function (Blueprint $table) {

            $table->id();

            // 🔥 Action Name
            $table->string('action_name');

            // 🔥 Action Type Key
            $table->string('type_key');

            $table->timestamps();

            $table->softDeletes();

            // ✅ prevent duplicate
            $table->unique(['action_name', 'type_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
