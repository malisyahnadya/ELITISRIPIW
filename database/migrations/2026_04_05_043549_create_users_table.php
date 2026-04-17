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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Standar Laravel
            $table->string('username', 30)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // Butuh buat Breeze
            $table->string('password'); // WAJIB 'password', jangan 'password_hash'
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable(); // Pake text biar legaan dikit
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->rememberToken(); // WAJIB buat fitur login tetap masuk
            $table->timestamps(); // Ini otomatis bikin created_at & updated_at standar Laravel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
