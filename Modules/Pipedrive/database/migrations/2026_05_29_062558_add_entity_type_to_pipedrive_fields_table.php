<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pipedrive_fields', function (Blueprint $table) {
            $table->string('entity_type', 50)
                ->nullable()
                ->after('field_type');
        });
    }

    public function down(): void
    {
        Schema::table('pipedrive_fields', function (Blueprint $table) {
            $table->dropColumn('entity_type');
        });
    }
};
