<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 75);
            $table->decimal('cost', 11, 2);
            $table->smallInteger('new_incomes');
            $table->smallInteger('stock_current');
            $table->smallInteger('stock_new');
            $table->smallInteger('consumed')->default(0);
            $table->date('expiration')->nullable();
            $table->boolean('active')->default(1);
            $table->foreignId('supplier_id')->constrained('supplier');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('kardex_id')->constrained('kardex');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('incomes');
    }
}
