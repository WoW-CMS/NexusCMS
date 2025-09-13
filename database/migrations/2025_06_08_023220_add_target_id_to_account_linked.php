<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_linked', function (Blueprint $table) {
            $table->unsignedBigInteger('target_id')->nullable()->before('realm_id');
        });
    }

    public function down(): void
    {
        Schema::table('account_linked', function (Blueprint $table) {
            $table->dropColumn('target_id');
        });
    }
};