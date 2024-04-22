<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKamarsTable extends Migration
{
    public function up()
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('nomor_kamar');
            $table->string('tipe_kamar');
            $table->decimal('harga', 10, 2);
            $table->enum('status', ['tersedia', 'dipesan', 'tidak tersedia'])->default('tersedia');
            $table->text('foto');
            $table->text('fasilitas');
            $table->text('description');
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kamars');
    }
}
