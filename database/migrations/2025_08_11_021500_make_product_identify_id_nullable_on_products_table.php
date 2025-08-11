<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Make product_identify_id nullable
        DB::statement("ALTER TABLE products MODIFY product_identify_id VARCHAR(255) NULL");
    }

    public function down(): void
    {
        // Revert to NOT NULL (adjust as needed if your DB had a default)
        DB::statement("ALTER TABLE products MODIFY product_identify_id VARCHAR(255) NOT NULL");
    }
};
