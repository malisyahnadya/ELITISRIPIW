<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('movies', 'tmdb_id')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('actors', 'tmdb_id')) {
            Schema::table('actors', function (Blueprint $table) {
                $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('directors', 'tmdb_id')) {
            Schema::table('directors', function (Blueprint $table) {
                $table->unsignedBigInteger('tmdb_id')->nullable()->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('movies', 'tmdb_id')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->dropColumn('tmdb_id');
            });
        }

        if (Schema::hasColumn('actors', 'tmdb_id')) {
            Schema::table('actors', function (Blueprint $table) {
                $table->dropColumn('tmdb_id');
            });
        }

        if (Schema::hasColumn('directors', 'tmdb_id')) {
            Schema::table('directors', function (Blueprint $table) {
                $table->dropColumn('tmdb_id');
            });
        }
    }
};