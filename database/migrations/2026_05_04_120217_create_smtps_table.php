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
        Schema::create('smtps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['default', 'customer', 'invoice', 'supplier'])
                ->default('default');

            $table->boolean('is_active')->default(true);

            $table->string('host');
            $table->integer('port')->default(587);

            $table->string('username');
            $table->string('password');

            $table->string('encryption')->nullable(); // tls, ssl, null

            $table->string('from_email');
            $table->string('from_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtps');
    }
};
