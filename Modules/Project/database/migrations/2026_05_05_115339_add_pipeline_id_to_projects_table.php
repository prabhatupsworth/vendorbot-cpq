<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->foreignId('pipeline_id')
                ->nullable()
                ->after('pipedrive_account_id')
                ->constrained('pipedrive_pipelines') // 🔥 your table name
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropForeign(['pipeline_id']);
            $table->dropColumn('pipeline_id');

        });
    }
};
