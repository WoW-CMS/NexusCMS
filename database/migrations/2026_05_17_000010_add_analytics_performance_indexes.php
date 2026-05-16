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
        Schema::table('analytics_sessions', function (Blueprint $table) {
            $table->index(['is_bot', 'created_at'], 'analytics_sessions_is_bot_created_idx');
        });

        Schema::table('analytics_page_views', function (Blueprint $table) {
            $table->index(['session_id', 'created_at'], 'analytics_page_views_session_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_page_views', function (Blueprint $table) {
            $table->dropIndex('analytics_page_views_session_created_idx');
        });

        Schema::table('analytics_sessions', function (Blueprint $table) {
            $table->dropIndex('analytics_sessions_is_bot_created_idx');
        });
    }
};
