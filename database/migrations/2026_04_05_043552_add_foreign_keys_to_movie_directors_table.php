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
        Schema::table('movie_directors', function (Blueprint $table) {
            $table->foreign(['director_id'], 'fk_md_director')->references(['id'])->on('directors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['movie_id'], 'fk_md_movie')->references(['id'])->on('movies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_directors', function (Blueprint $table) {
            $table->dropForeign('fk_md_director');
            $table->dropForeign('fk_md_movie');
        });
    }
};
