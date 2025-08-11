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
        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                if (!Schema::hasColumn('product_translations', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('product_translations', 'color')) {
                    $table->string('color')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('product_translations', 'location')) {
                    $columnsToDrop[] = 'location';
                }
                if (Schema::hasColumn('product_translations', 'color')) {
                    $columnsToDrop[] = 'color';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
