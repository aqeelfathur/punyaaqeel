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
       Schema::create('user_workouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id_nama')->on('users')->onDelete('cascade');
            
            // Mengubah tipe data program_id dari unsignedBigInteger menjadi string
            $table->string('program_id');
            $table->foreign('program_id')->references('id_program')->on('programs')->onDelete('cascade');
            
            $table->unsignedBigInteger('calender_id');
            $table->foreign('calender_id')->references('id_calender')->on('calenders')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_workouts');
    }
};