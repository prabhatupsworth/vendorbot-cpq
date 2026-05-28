<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run Migration
     */
    public function up(): void
    {
        Schema::table(
            'products',
            function (Blueprint $table) {

                $table->string('currency', 10)
                    ->nullable()
                    ->after('price');
            }
        );
    }

    /**
     * Reverse Migration
     */
    public function down(): void
    {
        Schema::table(
            'products',
            function (Blueprint $table) {

                $table->dropColumn(
                    'currency'
                );
            }
        );
    }
};
