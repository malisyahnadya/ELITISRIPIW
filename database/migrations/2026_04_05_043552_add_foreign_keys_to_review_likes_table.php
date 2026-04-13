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
        Schema::table('review_likes', function (Blueprint $table) {
            $table->foreign(['review_id'], 'fk_rl_review')->references(['id'])->on('reviews')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_rl_user')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_likes', function (Blueprint $table) {
            $table->dropForeign('fk_rl_review');
            $table->dropForeign('fk_rl_user');
        });
    }
};
