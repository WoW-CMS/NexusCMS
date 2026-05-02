<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // JSON columns for per-locale content
            $table->json('title_translations')->nullable()->after('title');
            $table->json('content_translations')->nullable()->after('content');
            $table->text('excerpt')->nullable()->after('content_translations');
        });

        // Seed default multilingual settings if not present
        $defaults = [
            'multilingual_enabled' => '0',
            'available_locales'    => '["en"]',
        ];

        foreach ($defaults as $key => $value) {
            \DB::table('settings')->insertOrIgnore([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'content_translations', 'excerpt']);
        });

        \DB::table('settings')->whereIn('key', ['multilingual_enabled', 'available_locales'])->delete();
    }
};
