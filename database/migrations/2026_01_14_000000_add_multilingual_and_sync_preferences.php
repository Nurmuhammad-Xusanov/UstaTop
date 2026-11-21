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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_ru');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 10)->default('uz')->after('role');
            $table->string('telegram_locale', 10)->nullable()->after('locale');
            $table->boolean('sync_telegram_lang')->default(true)->after('telegram_locale');
            $table->boolean('sync_theme')->default(true)->after('sync_telegram_lang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_ru', 'name_en']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['locale', 'telegram_locale', 'sync_telegram_lang', 'sync_theme']);
        });
    }
};
