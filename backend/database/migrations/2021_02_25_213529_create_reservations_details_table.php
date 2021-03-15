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
            $table->dateTime('arrival_date');
            $table->dateTime('departure_date');
            $table->smallInteger('accommodation');
            $table->smallInteger('quote')->default(0);

            $table->decimal('price', 11, 2);
            $table->decimal('sub', 11, 2);
            $table->boolean('ofert')->default(false);
            $table->string('guest', 200);
            $table->longText('description')->nullable();
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('coin_id')->constrained('coins');
            
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('type_service_id')->constrained('type_services');
            $table->foreignId('status_id')->constrained('status');
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
