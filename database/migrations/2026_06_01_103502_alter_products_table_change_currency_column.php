<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // If currency column exists
            $table->dropColumn('currency');

        });

        Schema::table('products', function (Blueprint $table) {

            $table->string('currency_code', 10)
                ->default('USD')
                ->after('price');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn('currency_code');

            $table->string('currency')
                ->nullable()
                ->after('price');

        });
    }
};
