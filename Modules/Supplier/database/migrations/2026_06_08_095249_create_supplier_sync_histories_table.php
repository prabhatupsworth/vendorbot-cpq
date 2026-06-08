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
        Schema::create('supplier_sync_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            $table->enum('sync_period', [
                '15_days',
                '30_days',
                '3_months',
                '6_months'
            ]);

            $table->integer('total_synced')->default(0);

            $table->timestamp('started_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->enum('status', [
                'pending',
                'completed',
                'failed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_sync_histories');
    }
};
