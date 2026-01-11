<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('gateway', 64);
            $table->string('transaction_id', 128)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 8)->default('USD');
            $table->unsignedInteger('dp_awarded')->default(0);
            $table->string('status', 32)->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'gateway', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_transactions');
    }
};

