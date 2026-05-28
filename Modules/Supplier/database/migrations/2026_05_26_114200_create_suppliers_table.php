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
        Schema::create('suppliers', function (Blueprint $table) {

            $table->id();

            $table->string('google_id')
                ->nullable()
                ->index();

            $table->string('name')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->boolean('status')
                ->default(0);

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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
