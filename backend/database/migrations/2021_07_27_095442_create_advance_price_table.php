<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_price', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 11, 2);
            $table->string('link', 500)->nullable();
            $table->string('document', 500)->nullable();
            $table->string('authorization_link')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_percentage');
            $table->foreignId('contract_id')->constrained('contracts');
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->foreignId('way_to_pay_id')->constrained('way_to_pay');
            $table->foreignId('coin_id')->constrained('coins');
            //$table->unique('contract_id');
            $table->boolean('pay')->default(false);
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
        Schema::dropIfExists('advance_price');
    }
}
