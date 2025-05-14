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
        Schema::create('komentars', function (Blueprint $table) {
            $table->string('id_komentar')->primary();
            $table->text('isi_komentar');
            $table->integer('jumlah_like_komentar')->default(0);
            $table->integer('jumlah_balasan_komentar')->default(0);
            $table->string('id_parent')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id_nama')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentars');
    }
};
