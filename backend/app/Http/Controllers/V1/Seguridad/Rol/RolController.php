<?php

namespace App\Http\Controllers\V1\Seguridad\Rol;

use Illuminate\Http\Request;
use App\Models\V1\Seguridad\Rol;
use App\Models\V1\Seguridad\Menu;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Seguridad\RolMenu;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class RolController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dato = Rol::with('rols_menus.menu')->get();
        return $this->showAll($dato);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules(), $this->messages());
        try {
            DB::beginTransaction();

            $rol = Rol::create($request->all());

            foreach ($request->menus as $value) {
                $padre = Menu::find($value['id']);
                RolMenu::firstOrCreate(['rol_id' => $rol->id, 'menu_id' => $padre->father]);
                RolMenu::firstOrCreate(['rol_id' => $rol->id, 'menu_id' => $value['id']]);
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguridad\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        try {
            $rol->forceDelete();
            return $this->successResponse('Registro desactivado');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => 'required|max:25|unique:rols,name',
            'menus.*.id' => 'required|integer|exists:menus,id'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages($id = null)
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.max'  => 'El nombre debe tener menos de :max caracteres.',
            'name.unique'  => 'El nombre del rol ingresado ya existe en el sistema.',

            'menus.*.id.required' => 'El menú es obligatorio',
            'menus.*.id.integer' => 'El menú no es un número',
            'menus.*.id.exists' => 'El menú no existe en la base de datos'
        ];
    }
}
