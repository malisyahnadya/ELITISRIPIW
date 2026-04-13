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
        Schema::table('movie_actors', function (Blueprint $table) {
            $table->foreign(['actor_id'], 'fk_ma_actor')->references(['id'])->on('actors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['movie_id'], 'fk_ma_movie')->references(['id'])->on('movies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_actors', function (Blueprint $table) {
            $table->dropForeign('fk_ma_actor');
            $table->dropForeign('fk_ma_movie');
        });
    }
};
