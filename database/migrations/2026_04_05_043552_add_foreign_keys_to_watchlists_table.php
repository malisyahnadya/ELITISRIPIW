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
        Schema::table('watchlists', function (Blueprint $table) {
            $table->foreign(['movie_id'], 'fk_watch_movie')->references(['id'])->on('movies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_watch_user')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->dropForeign('fk_watch_movie');
            $table->dropForeign('fk_watch_user');
        });
    }
};
