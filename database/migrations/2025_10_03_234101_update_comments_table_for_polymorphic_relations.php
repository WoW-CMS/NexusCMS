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
        Schema::table('comments', function (Blueprint $table) {
            // Eliminar la columna news_id
            $table->dropColumn('news_id');
            
            // Agregar columnas para la relación polimórfica
            $table->morphs('commentable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Eliminar las columnas de la relación polimórfica
            $table->dropMorphs('commentable');
            
            // Restaurar la columna news_id
            $table->unsignedBigInteger('news_id')->after('id');
        });
    }
};
