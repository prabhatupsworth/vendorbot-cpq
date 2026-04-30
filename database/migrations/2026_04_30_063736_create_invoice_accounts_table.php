<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_accounts', function (Blueprint $table) {
            $table->id();

            // ENUM: lexware, manual, other
            $table->enum('type', ['lexware', 'manual', 'other']);

            $table->string('api_key')->nullable();
            $table->string('base_url')->nullable();

            $table->boolean('is_verified')->default(false);

            $table->decimal('default_tax', 8, 2)->default(0);

            $table->string('currency')->nullable();

            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_accounts');
    }
};
