<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinnacleReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binnacle_reservations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('active')->default(true);
            $table->smallInteger('days')->default(60);
            $table->smallInteger('subtraction')->default(0);
            $table->foreignId('movement_id')->constrained('movements');

            $table->foreignId('type_service_id')->constrained('type_services');
            $table->foreignId('reservation_detail_id')->constrained('reservations_details');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('binnacle_reservations');
    }
}
