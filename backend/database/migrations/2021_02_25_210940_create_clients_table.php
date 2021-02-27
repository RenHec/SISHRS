<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 15)->unique();
            $table->string('first_name', 50);
            $table->string('second_name', 50)->nullable();
            $table->string('surname', 50);
            $table->string('second_surname', 50)->nullable();
            $table->string('email', 75)->unique();
            $table->string('ubication', 100)->nullable();

            $table->foreignId('departament_id')->constrained('departaments');
            $table->foreignId('municipality_id')->constrained('municipalities');
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
        Schema::dropIfExists('clients');
    }
}
