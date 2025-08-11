<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->string('btn_text')->nullable()->after('desc');
            $table->string('btn_url')->nullable()->after('btn_text');
        });
    }

    public function down(): void
    {
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->dropColumn(['btn_text', 'btn_url']);
        });
    }
};
