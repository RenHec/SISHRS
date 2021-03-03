<?php

namespace App\Http\Controllers\V1\Catalogo\TypeCharge;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\TypeCharge;
use App\Http\Controllers\ApiController;

class TypeChargeController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/typecharge",
     *      operationId="getTypeCharges",
     *      tags={"Type Charge"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de cobro registrados en la base de datos.",
     *      description="Retorna un array de tipos de cobro.",
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
        $data = TypeCharge::all();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/catalogo/typecharge",
     *      operationId="postTypeCharge",
     *      tags={"Type Charge"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo tipo de cobro en el sistema.",
     *      description="Retorna el objeto del tipo de cobro creado.",
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
            TypeCharge::create($request->all());
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/catalogo/typecharge/{typecharge}",
     *      operationId="updateTypeCharge",
     *      tags={"Type Charge"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el tipo de cobro seleccionado.",
     *      description="Retorna el objeto del tipo de cobro actualizado.",
     *      @OA\Parameter(
     *          description="ID del tipo de cobro para actualizar",
     *          in="path",
     *          name="typecharge",
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
    public function update(Request $request, TypeCharge $typecharge)
    {
        $this->validate($request, $this->rules($typecharge->id), $this->messages());

        try {
            $typecharge->name = $request->name;

            if (!$typecharge->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $typecharge->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/catalogo/typecharge/{typecharge}   ",
     *      operationId="deleteTypeCharge",
     *      tags={"Type Charge"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el tipo de cobro seleccionado.",
     *      description="Retorna el objeto del tipo de cobro eliminado.",
     *      @OA\Parameter(
     *          description="ID del tipo de cobro para eliminar",
     *          in="path",
     *          name="typecharge",
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
    public function destroy($typecharge)
    {
        $typecharge = TypeCharge::withTrashed()->find($typecharge);
        if (is_null($typecharge->deleted_at)) {
            $typecharge->delete();
            $message = 'descativado';
        } else {
            $typecharge->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => is_null($id) ? 'required|max:50|unique:type_charge,name' : "required|max:50|unique:type_charge,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre del tipo de cobro es obligatorio.',
            'name.max'  => 'El nombre del tipo de cobro debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del tipo de cobro ingresado ya existe en el sistema.',
        ];
    }
}