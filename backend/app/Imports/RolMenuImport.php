<?php

namespace App\Imports;

use App\Models\V1\Seguridad\Rol;
use App\Models\V1\Seguridad\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Seguridad\RolMenu;
use Maatwebsite\Excel\Concerns\ToCollection;

class RolMenuImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if ($key != 0) {
                echo $value[0] . PHP_EOL;
                if (!is_null($value[0])) {
                    DB::beginTransaction();
                    $menu = new Menu();
                    $menu->name = $value[0];
                    $menu->route_name = $value[1];
                    $menu->father = is_null($value[2]) ? 0 : Menu::where('name', $value[2])->first()->id;
                    $menu->hide = $value[3];
                    $menu->icon = $value[4];
                    $menu->save();

                    $combicacion = $value[5];
                    $roles = array();
                    switch ($combicacion) {
                        case 'ADMIN_OPE':
                            $admin = Rol::firstOrCreate(['name' => $value[6]]);
                            $operador = Rol::firstOrCreate(['name' => $value[7]]);
                            array_push($roles, $admin->id);
                            array_push($roles, $operador->id);
                            break;

                        case 'ADMIN':
                            $admin = Rol::firstOrCreate(['name' => $value[6]]);
                            array_push($roles, $admin->id);
                            break;
                    }

                    foreach ($roles as $value) {
                        $menu_rol = new RolMenu();
                        $menu_rol->rol_id = $value;
                        $menu_rol->menu_id = $menu->id;
                        $menu_rol->save();
                    }
                    DB::commit();
                }
            }
        }
    }
}
