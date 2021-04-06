<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 11, 2);
            $table->decimal('discount', 11, 2)->nullable();
            $table->foreignId('product_id')->constrained('products');
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
        Schema::dropIfExists('change_prices');
    }
}
