<?php

namespace Database\Seeders;

use App\Imports\RolMenuImport;
use Illuminate\Database\Seeder;
use App\Imports\MunicipioImport;
use App\Models\V1\Catalogo\Status;
use App\Imports\DepartamentoImport;
use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Catalogo\TypeBed;
use App\Models\V1\Catalogo\TypeCharge;
use App\Models\V1\Catalogo\TypeMessage;
use App\Models\V1\Catalogo\TypeRoom;
use App\Models\V1\Catalogo\TypeService;
use App\Models\V1\Principal\Client;
use App\Models\V1\Seguridad\Usuario;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\V1\Seguridad\UsuarioRol;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Migrando data para los roles, menus y asociando menu al rol correspondiente
        Excel::import(new RolMenuImport, 'database/seeders/Catalogos/RolMenu.xlsx');

        //Migrando Departamento y Municipios asociados
        Excel::import(new DepartamentoImport, 'database/seeders/Catalogos/Departamentos.xlsx');
        Excel::import(new MunicipioImport, 'database/seeders/Catalogos/Municipios.xlsx');

        $insert = new Coin();
        $insert->symbol = 'Q';
        $insert->name = 'Quetzales';
        $insert->change = 0;
        $insert->save();

        $insert = new Status();
        $insert->name = 'pendiente';
        $insert->save();
        $insert = new Status();
        $insert->name = 'en prceso';
        $insert->save();
        $insert = new Status();
        $insert->name = 'desocupado';
        $insert->save();
        $insert = new Status();
        $insert->name = 'anulado';
        $insert->save();
        $insert = new Status();
        $insert->name = 'cancelación';
        $insert->save();
        $insert = new Status();
        $insert->name = 'confirmado';
        $insert->save();

        $insert = new TypeBed();
        $insert->name = 'Litera';
        $insert->save();
        $insert = new TypeBed();
        $insert->name = 'Cama';
        $insert->save();

        $insert = new TypeService();
        $insert->name = 'Hotel';
        $insert->save();
        $insert = new TypeService();
        $insert->name = 'SPA';
        $insert->save();
        $insert = new TypeService();
        $insert->name = 'Belleza';
        $insert->save();
        $insert = new TypeService();
        $insert->name = 'Clínica';
        $insert->save();

        $insert = new TypeRoom();
        $insert->name = 'Bungalo';
        $insert->type_service_id = 1;
        $insert->save();

        $insert = new TypeRoom();
        $insert->name = 'Principal';
        $insert->type_service_id = 1;
        $insert->save();

        $insert = new TypeRoom();
        $insert->name = 'Cabina';
        $insert->type_service_id = 2;
        $insert->save();

        $insert = new TypeRoom();
        $insert->name = 'Silla';
        $insert->type_service_id = 3;
        $insert->save();

        $insert = new TypeRoom();
        $insert->name = 'Cuarto';
        $insert->type_service_id = 4;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Día';
        $insert->type_service_id = 1;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Noche';
        $insert->type_service_id = 1;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Hora';
        $insert->type_service_id = 2;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Minutos';
        $insert->type_service_id = 2;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Hora';
        $insert->type_service_id = 3;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Minutos';
        $insert->type_service_id = 3;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Hora';
        $insert->type_service_id = 4;
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Minutos';
        $insert->type_service_id = 4;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Masaje 1';
        $insert->time = 45;
        $insert->price = 50;
        $insert->coin_id = 1;
        $insert->type_service_id = 2;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Masaje 2';
        $insert->time = 60;
        $insert->price = 60;
        $insert->coin_id = 1;
        $insert->type_service_id = 2;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Belleza 1';
        $insert->time = 60;
        $insert->price = 60;
        $insert->coin_id = 1;
        $insert->type_service_id = 3;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Belleza 2';
        $insert->time = 30;
        $insert->price = 30;
        $insert->coin_id = 1;
        $insert->type_service_id = 3;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Clínica 1';
        $insert->time = 15;
        $insert->price = 25;
        $insert->coin_id = 1;
        $insert->type_service_id = 4;
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Clínica 2';
        $insert->time = 60;
        $insert->price = 60;
        $insert->coin_id = 1;
        $insert->type_service_id = 4;
        $insert->save();

        $insert = new Movement();
        $insert->name = 'Programada';
        $insert->save();
        $insert = new Movement();
        $insert->name = 'Reubicada';
        $insert->save();
        $insert = new Movement();
        $insert->name = 'Cancelada';
        $insert->save();

        $insert = new Client();
        $insert->nit = '765894';
        $insert->first_name = 'Héctor';
        $insert->second_name = 'Renato';
        $insert->surname = 'de la Cruz';
        $insert->second_surname = 'Ojeda';
        $insert->email = 'emisor.tarea@gmail.com';
        $insert->ubication = 'Chiquililla';
        $insert->departament_id = 1;
        $insert->municipality_id = 1;
        $insert->save();
        
        Usuario::factory(100)->create();
        UsuarioRol::factory(200)->create();    
        
        Artisan::call('passport:install');
    }
}
