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
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_category')->default(false);
            $table->unsignedBigInteger('latest_thread_id')->nullable();
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('forums')->onDelete('cascade');
        });

        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_sticky')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('view_count')->default(0);
            $table->unsignedBigInteger('first_post_id')->nullable();
            $table->unsignedBigInteger('latest_post_id')->nullable();
            $table->timestamps();
            
            $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->boolean('is_first_post')->default(false);
            $table->timestamps();
            
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Add foreign key constraints for latest_thread_id, first_post_id and latest_post_id
        Schema::table('forums', function (Blueprint $table) {
            $table->foreign('latest_thread_id')->references('id')->on('threads')->onDelete('set null');
        });
        
        Schema::table('threads', function (Blueprint $table) {
            $table->foreign('first_post_id')->references('id')->on('posts')->onDelete('set null');
            $table->foreign('latest_post_id')->references('id')->on('posts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropForeign(['first_post_id']);
            $table->dropForeign(['latest_post_id']);
        });
        
        Schema::table('forums', function (Blueprint $table) {
            $table->dropForeign(['latest_thread_id']);
        });
        
        Schema::dropIfExists('posts');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('forums');
    }
};