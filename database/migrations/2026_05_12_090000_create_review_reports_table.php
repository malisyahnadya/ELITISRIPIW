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
        Schema::create('review_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('idx_report_user');
            $table->unsignedBigInteger('review_id')->index('idx_report_review');
            $table->string('reason', 120)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'review_id'], 'uq_report_user_review');
            $table->index(['review_id', 'created_at'], 'idx_report_review_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_reports');
    }
};
