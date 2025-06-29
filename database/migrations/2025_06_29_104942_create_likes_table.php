<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id'); // relasi ke posts.id
            $table->unsignedBigInteger('id_nama');
            $table->foreign('id_nama')->references('id_nama')->on('users')->onDelete('cascade');

            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->unique(['post_id', 'id_nama']); // supaya user hanya bisa like sekali per post
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
}
