<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->string('google_id')
                ->nullable()
                ->index();

            $table->string('name')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->boolean('status')
                ->default(false);

            $table->string('email')
                ->nullable()
                ->index();

            $table->string('phone')
                ->nullable();

            $table->text('url')
                ->nullable();

            $table->text('social_facebook')
                ->nullable();

            $table->text('social_instagram')
                ->nullable();

            $table->string('country')
                ->nullable();

            $table->string('zip')
                ->nullable();

            $table->text('street')
                ->nullable();

            $table->string('lon')
                ->nullable();

            $table->string('lat')
                ->nullable();

            $table->text('daysoff')
                ->nullable();

            $table->string('capacity')
                ->nullable();

            $table->string('cp_title')
                ->nullable();

            $table->string('cp_name')
                ->nullable();

            $table->text('notice')
                ->nullable();

            $table->text('notice_intern')
                ->nullable();

            $table->timestamps();

            // Prevent duplicate email within same project
            $table->unique(
                ['project_id', 'email'],
                'project_email_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
