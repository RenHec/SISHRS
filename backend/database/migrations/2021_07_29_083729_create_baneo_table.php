<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaneoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baneo', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('level');
            $table->string('site_url');
            $table->ipAddress('ip');
            $table->foreignId('reservation_id')->constrained('reservations');
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
        Schema::dropIfExists('baneo');
    }
}
