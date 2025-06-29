<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // primary key: id
            $table->unsignedBigInteger('id_nama');
            $table->foreign('id_nama')->references('id_nama')->on('users')->onDelete('cascade');
            $table->text('isi');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}
