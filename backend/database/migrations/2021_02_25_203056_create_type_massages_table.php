<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeMassagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_massages', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('time');
            $table->string('name', 50);
            $table->decimal('price', 11, 2);
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('type_service_id')->constrained('type_services');
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
        Schema::dropIfExists('type_massages');
    }
}
