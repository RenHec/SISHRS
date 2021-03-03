<?php

namespace Database\Seeders;

use App\Imports\RolMenuImport;
use Illuminate\Database\Seeder;
use App\Imports\MunicipioImport;
use App\Models\V1\Catalogo\Status;
use App\Imports\DepartamentoImport;
use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Catalogo\TypeBed;
use App\Models\V1\Catalogo\TypeCharge;
use App\Models\V1\Catalogo\TypeMessage;
use App\Models\V1\Catalogo\TypeRoom;
use App\Models\V1\Catalogo\TypeService;
use App\Models\V1\Seguridad\Usuario;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\V1\Seguridad\UsuarioRol;

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

        $insert = new TypeCharge();
        $insert->name = 'Día';
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Noche';
        $insert->save();

        $insert = new TypeCharge();
        $insert->name = 'Hora';
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Masaje 1';
        $insert->save();

        $insert = new TypeMessage();
        $insert->name = 'Masaje 2';
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
        
        Usuario::factory(100)->create();
        UsuarioRol::factory(200)->create();      
    }
}
