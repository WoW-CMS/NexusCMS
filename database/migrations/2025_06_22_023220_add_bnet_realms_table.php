<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            $table->boolean('bnet')->nullable()->after('auth_database');
        });
    }

    public function down(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            $table->dropColumn('bnet');
        });
    }
};