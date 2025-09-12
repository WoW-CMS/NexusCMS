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
        Schema::create('realms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hostname');
            $table->integer('expansion');
            $table->string('emulator')->default('Trinity');
            $table->integer('port');
            $table->json('auth_database');
            $table->json('world_database');
            $table->string('console_hostname');
            $table->string('console_username');
            $table->string('console_password');
            $table->string('console_urn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realms');
    }
};
