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
        Schema::create(
            'supplier_category_relationship',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Supplier
                |--------------------------------------------------------------------------
                */

                $table->foreignId('supplier_id')
                    ->constrained('suppliers')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Scrap Category
                |--------------------------------------------------------------------------
                */

                $table->foreignId('category_id')
                    ->constrained('scrap_categories')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Main Category
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_main')
                    ->default(false);

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Relation
                |--------------------------------------------------------------------------
                */

                $table->unique([
                    'supplier_id',
                    'category_id'
                ]);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'supplier_category_relationship'
        );
    }
};
