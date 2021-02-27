<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsOfertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations_oferts', function (Blueprint $table) {
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->foreignId('reservation_detail_id')->constrained('reservations_details');
            $table->foreignId('ofert_room_id')->constrained('oferts_rooms');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations_oferts');
    }
}
