<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('amount');
            $table->decimal('price', 11, 2);
            $table->decimal('price_discount', 11, 2)->nullable();
            $table->decimal('price_sub', 11, 2);

            $table->decimal('cost', 11, 2);
            $table->decimal('individual', 11, 2)->nullable();
            $table->decimal('cost_sub', 11, 2);

            $table->foreignId('reservation_product_id')->constrained('reservations_products');
            $table->foreignId('kardex_id')->constrained('kardex');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('income_id')->constrained('incomes');
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
        Schema::dropIfExists('sales');
    }
}
