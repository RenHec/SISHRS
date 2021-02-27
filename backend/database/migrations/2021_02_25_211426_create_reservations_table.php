<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('nit', 15)->default('CF');
            $table->string('name', 200);
            $table->string('ubication', 350)->nullable();

            $table->decimal('total', 11, 2);
            $table->dateTime('arrival_date');
            $table->dateTime('departure_date');
            $table->smallInteger('accommodation');

            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('status_id')->constrained('status');
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
        Schema::dropIfExists('reservations');
    }
}
