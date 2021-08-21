<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations_products', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('amount');
            $table->decimal('price', 11, 2);
            $table->decimal('discount', 11, 2)->nullable();
            $table->decimal('sub_total', 11, 2);
            $table->string('authorization_code', 100);
            $table->boolean('pagado')->default(false);

            $table->foreignId('reservation_id')->constrained('reservations');
            $table->foreignId('kardex_id')->constrained('kardex');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');
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
        Schema::dropIfExists('reservations_products');
    }
}
