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
        Schema::create('detail_programs', function (Blueprint $table) {
            $table->id();
            $table->string('id_program');
            $table->string('id_gerakan');
            $table->foreign('id_program')->references('id_program')->on('programs')->onDelete('cascade');
            $table->foreign('id_gerakan')->references('id_gerakan')->on('gerakans')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_programs');
    }
};
