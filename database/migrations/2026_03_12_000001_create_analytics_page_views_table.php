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
        Schema::create('analytics_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('analytics_sessions')->onDelete('cascade');
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('referrer')->nullable();
            $table->integer('time_on_page')->default(0);
            $table->boolean('bounced')->default(false);
            $table->timestamps();

            $table->index('session_id');
            $table->index('page_url');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_page_views');
    }
};
