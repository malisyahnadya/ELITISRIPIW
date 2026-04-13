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
        Schema::table('movie_genres', function (Blueprint $table) {
            $table->foreign(['genre_id'], 'fk_mg_genre')->references(['id'])->on('genres')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['movie_id'], 'fk_mg_movie')->references(['id'])->on('movies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_genres', function (Blueprint $table) {
            $table->dropForeign('fk_mg_genre');
            $table->dropForeign('fk_mg_movie');
        });
    }
};
