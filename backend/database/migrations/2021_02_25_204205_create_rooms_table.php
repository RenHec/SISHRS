<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('number');
            $table->string('name', 100);
            $table->smallInteger('number_adults');
            $table->smallInteger('number_children');
            $table->smallInteger('amount_people');
            $table->smallInteger('amount_bed');
            $table->smallInteger('amount_pets');
            $table->smallInteger('resta')->default(0);
            $table->longText('description');
            $table->boolean('pets')->default(false);
            $table->foreignId('type_service_id')->constrained('type_services');
            $table->foreignId('type_bed_id')->constrained('type_beds');
            $table->foreignId('type_room_id')->constrained('type_rooms');
            $table->softDeletes();
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
        Schema::dropIfExists('rooms');
    }
}
