<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertsRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oferts_rooms', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('accommodation');
            $table->decimal('price', 11, 2);
            $table->longText('observation');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('active');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('type_charge_id')->constrained('type_charge');
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
        Schema::dropIfExists('oferts_rooms');
    }
}
