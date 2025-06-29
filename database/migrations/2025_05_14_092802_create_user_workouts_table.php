<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_workouts', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id_nama')->on('users')->onDelete('cascade');

            // Relasi ke programs
            $table->string('program_id');
            $table->foreign('program_id')->references('id_program')->on('programs')->onDelete('cascade');

            // Status (optional: bisa 'scheduled', 'in-progress', dll)
            $table->string('status')->default('scheduled');

            $table->timestamps(); // <== sudah ada created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_workouts');
    }
};
