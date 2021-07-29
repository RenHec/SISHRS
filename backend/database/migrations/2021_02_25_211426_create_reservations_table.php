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
            $table->boolean('event')->default(false);
            $table->string('responsable', 200)->nullable();
            $table->boolean('reserva')->default(1);

            $table->decimal('advance_price', 11, 2)->default(0);
            $table->decimal('total', 11, 2);
            $table->decimal('total_reservation', 11, 2)->default(0);
            $table->decimal('total_product', 11, 2)->default(0);
            $table->decimal('total_restaurant', 11, 2)->default(0);

            $table->string('document', 100)->nullable(); //Guardaremos la imagen en el local storage

            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('status_id')->constrained('status');

            $table->boolean('payment')->default(0);
            $table->foreignId('way_to_pay_id')->constrained('way_to_pay');

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
