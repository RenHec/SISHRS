<?php


use App\Models\V1\Seguridad\Usuario;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('cui', 15)->unique();
            $table->string('first_name', 50);
            $table->string('second_name', 50)->nullable();
            $table->string('surname', 50);
            $table->string('second_surname', 50)->nullable();
            $table->string('married_name', 50)->nullable();
            $table->string('admin')->default(Usuario::USUARIO_REGULAR);
            $table->string('photo', 100)->nullable(); //Guardaremos la imagen en el local storage
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('observation', 500)->nullable();
            $table->string('ubication', 100)->nullable();
            $table->string('phone', 25)->nullable();

            $table->foreignId('departament_id')->constrained('departaments');
            $table->foreignId('municipality_id')->constrained('municipalities');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
