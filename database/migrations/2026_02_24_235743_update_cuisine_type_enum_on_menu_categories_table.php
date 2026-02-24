<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ✅ tambahkan ini

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
        ALTER TABLE menu_categories
        MODIFY COLUMN cuisine_type
        ENUM('sunda', 'betawi', 'minuman', 'lainnya', 'makanan', 'dessert')
        NOT NULL DEFAULT 'makanan'
    ");

        DB::table('menu_categories')
            ->whereIn('cuisine_type', ['sunda', 'betawi', 'lainnya'])
            ->update(['cuisine_type' => 'makanan']);

        DB::statement("
        ALTER TABLE menu_categories
        MODIFY COLUMN cuisine_type
        ENUM('makanan', 'minuman', 'dessert')
        NOT NULL DEFAULT 'makanan'
    ");
    }

    public function down(): void
    {
        DB::statement("
        ALTER TABLE menu_categories
        MODIFY COLUMN cuisine_type
        ENUM('makanan', 'minuman', 'dessert', 'sunda', 'betawi', 'lainnya')
        NOT NULL DEFAULT 'lainnya'
    ");

        DB::table('menu_categories')
            ->where('cuisine_type', 'makanan')
            ->update(['cuisine_type' => 'sunda']);

        DB::table('menu_categories')
            ->where('cuisine_type', 'dessert')
            ->update(['cuisine_type' => 'lainnya']);

        DB::statement("
        ALTER TABLE menu_categories
        MODIFY COLUMN cuisine_type
        ENUM('sunda', 'betawi', 'minuman', 'lainnya')
        NOT NULL DEFAULT 'lainnya'
    ");
    }
};
