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
            $table->smallInteger('amount_people');
            $table->smallInteger('amount_bed');
            $table->decimal('price', 11, 2);
            $table->longText('description');
            $table->boolean('pets')->default(false);
            $table->foreignId('type_service_id')->constrained('type_services');
            $table->foreignId('type_bed_id')->constrained('type_beds');
            $table->foreignId('type_room_id')->constrained('type_rooms');
            $table->foreignId('coin_id')->constrained('coins');
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
