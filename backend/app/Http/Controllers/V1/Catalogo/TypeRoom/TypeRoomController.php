<?php

namespace App\Http\Controllers\V1\Catalogo\TypeRoom;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\TypeRoom;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class TypeRoomController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/typeroom",
     *      operationId="getTypeRooms",
     *      tags={"Type Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de habitación registradas en la base de datos.",
     *      description="Retorna un array de tipos de habitación.",
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
        $data = TypeRoom::all();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/catalogo/typeroom",
     *      operationId="postTypeRoom",
     *      tags={"Type Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo tipo de habitación en el sistema.",
     *      description="Retorna el objeto del tipo de habitación creado.",
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
            TypeRoom::create($request->all());
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/catalogo/typeroom/{typeroom}",
     *      operationId="updateTypeRoom",
     *      tags={"Type Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el tipo de habitación seleccionado.",
     *      description="Retorna el objeto del tipo de habitación actualizado.",
     *      @OA\Parameter(
     *          description="ID del tipo de habitación para actualizar",
     *          in="path",
     *          name="typeroom",
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
    public function update(Request $request, TypeRoom $typeroom)
    {
        $this->validate($request, $this->rules($typeroom->id), $this->messages());

        try {
            $typeroom->name = $request->name;

            if (!$typeroom->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $typeroom->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/catalogo/typeroom/{typeroom}   ",
     *      operationId="deleteTypeRoom",
     *      tags={"Type Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el tipo de habitación seleccionado.",
     *      description="Retorna el objeto del tipo de habitación eliminado.",
     *      @OA\Parameter(
     *          description="ID del tipo de habitación para eliminar",
     *          in="path",
     *          name="typeroom",
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
    public function destroy(TypeRoom $typeroom)
    {
        try {
            $typeroom->forceDelete();
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
            'name' => is_null($id) ? 'required|max:50|unique:type_rooms,name' : "required|max:50|unique:type_rooms,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre del tipo de habitación es obligatorio.',
            'name.max'  => 'El nombre del tipo de habitación debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del tipo de habitación ingresado ya existe en el sistema.',
        ];
    }
}
