<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Allow amount in enum
        DB::statement("
            ALTER TABLE products
            MODIFY discount_type
            ENUM('fixed','amount','percentage')
            NULL
        ");

        // Step 2: Update existing data
        DB::table('products')
            ->where('discount_type', 'fixed')
            ->update([
                'discount_type' => 'amount'
            ]);

        // Step 3: Remove fixed from enum
        DB::statement("
            ALTER TABLE products
            MODIFY discount_type
            ENUM('amount','percentage')
            NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE products
            MODIFY discount_type
            ENUM('fixed','amount','percentage')
            NULL
        ");

        DB::table('products')
            ->where('discount_type', 'amount')
            ->update([
                'discount_type' => 'fixed'
            ]);

        DB::statement("
            ALTER TABLE products
            MODIFY discount_type
            ENUM('fixed','percentage')
            NULL
        ");
    }
};
