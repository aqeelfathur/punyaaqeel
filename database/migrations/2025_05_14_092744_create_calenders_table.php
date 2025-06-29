<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendersTable extends Migration
{
    public function up()
    {
        Schema::create('calenders', function (Blueprint $table) {
            $table->id('id_calender'); // Primary key

            // Format YYYY-MM-DD dan harus unik agar 1 tanggal = 1 row
            $table->date('tanggal_penuh')->unique();

            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('calenders');
    }
}
