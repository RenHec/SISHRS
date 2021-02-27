<?php

namespace App\Http\Controllers\V1\Catalogo\TypeBed;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\TypeBed;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class TypeBedController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/typebed",
     *      operationId="getTypeBeds",
     *      tags={"Type Bed"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de cama registradas en la base de datos.",
     *      description="Retorna un array de tipos de cama.",
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function index()
    {
        $data = TypeBed::all();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/catalogo/typebed",
     *      operationId="postTypeBed",
     *      tags={"Type Bed"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo tipo de cama en el sistema.",
     *      description="Retorna el objeto del tipo de cama creado.",
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {
            TypeBed::create($request->all());
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/catalogo/typebed/{typebed}",
     *      operationId="updateTypeBed",
     *      tags={"Type Bed"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el tipo de cama seleccionado.",
     *      description="Retorna el objeto del tipo de cama actualizado.",
     *      @OA\Parameter(
     *          description="ID del tipo de cama para actualizar",
     *          in="path",
     *          name="typebed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function update(Request $request, TypeBed $typebed)
    {
        $this->validate($request, $this->rules($typebed->id), $this->messages());

        try {
            $typebed->name = $request->name;

            if (!$typebed->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $typebed->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/catalogo/typebed/{typebed}   ",
     *      operationId="deleteTypeBed",
     *      tags={"Type Bed"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el tipo de cama seleccionado.",
     *      description="Retorna el objeto del tipo de cama eliminado.",
     *      @OA\Parameter(
     *          description="ID del tipo de cama para eliminar",
     *          in="path",
     *          name="typebed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function destroy(TypeBed $typebed)
    {
        try {
            $typebed->forceDelete();
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
            'name' => is_null($id) ? 'required|max:50|unique:type_beds,name' : "required|max:50|unique:type_beds,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre del tipo de cama es obligatorio.',
            'name.max'  => 'El nombre del tipo de cama debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del tipo de cama ingresado ya existe en el sistema.',
        ];
    }
}
