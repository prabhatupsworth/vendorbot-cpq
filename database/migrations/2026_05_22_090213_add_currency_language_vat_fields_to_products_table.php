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
        Schema::table('projects', function (Blueprint $table) {

            $table->string('currency_code', 10)
                ->nullable()
                ->after('event_name');

            $table->string('language_code', 10)
                ->nullable()
                ->after('currency_code');

            $table->decimal('vat', 10, 2)
                ->default(0)
                ->after('language_code');

            $table->tinyInteger('vat_status')
                ->default(0)
                ->comment('0 = Excluded, 1 = Included')
                ->after('vat');

            $table->foreign('currency_code')
                ->references('code')
                ->on('currencies')
                ->nullOnDelete();

            $table->foreign('language_code')
                ->references('code')
                ->on('languages')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropForeign(['currency_code']);
            $table->dropForeign(['language_code']);

            $table->dropColumn([
                'currency_code',
                'language_code',
                'vat',
                'vat_status',
            ]);
        });
    }
};
