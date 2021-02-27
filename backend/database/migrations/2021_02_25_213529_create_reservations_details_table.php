<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 11, 2);
            $table->boolean('ofert')->default(false);
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('coin_id')->constrained('coins');
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
        Schema::dropIfExists('reservations_details');
    }
}
