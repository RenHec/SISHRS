<?php

namespace App\Http\Controllers\V1\Catalogo\Supplier;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\Supplier;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class SupplierController extends ApiController
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
        $data = Supplier::get();
        return $this->showAll($data);
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
            $data = $request->all();

            Supplier::create($data);
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\V1\Catalogo\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request, $this->rules($supplier->id), $this->messages());

        try {
            $supplier->name = $request->name;

            if (!$supplier->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $supplier->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\V1\Catalogo\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->forceDelete();
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
            'name' => is_null($id) ? 'required|max:50|unique:supplier,name' : "required|max:50|unique:supplier,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre del proveedor es obligatorio.',
            'name.max'  => 'El nombre del proveedor debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del proveedor ingresado ya existe en el sistema.',
        ];
    }
}
