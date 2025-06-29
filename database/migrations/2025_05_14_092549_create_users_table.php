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
            $table->id('id_nama'); // Gunakan id standar Laravel
            $table->string('username', 30);
            $table->string('password'); // Gunakan password standar Laravel
            $table->boolean('is_admin')->default(false);
            $table->string('email');
            $table->string('phone_number');
            $table->string('profile_image')->nullable(); // Kolom untuk menyimpan path/nama file gambar profil
            $table->timestamps();
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