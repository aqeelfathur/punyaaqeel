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
        Schema::create('gerakans', function (Blueprint $table) {
            $table->string('id_gerakan')->primary();
            $table->string('nama_gerakan', 30);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('created_by')->references('id_nama')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerakans');
    }
};
