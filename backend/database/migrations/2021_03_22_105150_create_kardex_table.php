<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKardexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('stock');
            $table->smallInteger('notify');
            $table->decimal('price', 11, 2);
            $table->decimal('discount', 11, 2)->nullable();
            $table->dateTime('date_entry')->nullable();
            $table->dateTime('date_egress')->nullable();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('kardex_status_id')->constrained('kardex_status');
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
        Schema::dropIfExists('kardex');
    }
}
