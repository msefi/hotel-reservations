<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('kamar_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar');
            $table->integer('jumlah_tamu');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['dipesan', 'selesai'])->default('dipesan');
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('kamar_id')->references('id')->on('kamars')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservasis');
    }
}
