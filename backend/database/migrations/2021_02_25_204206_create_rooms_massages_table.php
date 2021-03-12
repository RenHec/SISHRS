<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsMassagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_massages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_massage_id')->constrained('type_massages');
            $table->foreignId('room_id')->constrained('rooms');
            $table->boolean('web')->default(false);
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
        Schema::dropIfExists('rooms_massages');
    }
}
