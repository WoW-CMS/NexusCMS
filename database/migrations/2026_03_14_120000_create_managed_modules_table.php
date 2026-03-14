<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('managed_modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name', 100)->unique();
            $table->boolean('enabled')->default(true);
            $table->string('module_type', 20)->default('core');
            $table->timestamps();

            $table->index('module_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('managed_modules');
    }
};
