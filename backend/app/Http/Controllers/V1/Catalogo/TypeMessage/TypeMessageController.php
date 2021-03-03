<?php

namespace App\Http\Controllers\V1\Catalogo\TypeMessage;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\TypeMessage;

class TypeMessageController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/typemessage",
     *      operationId="getTypeMessages",
     *      tags={"Type Message"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de mesaje registrados en la base de datos.",
     *      description="Retorna un array de tipos de mesaje.",
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
        $data = TypeMessage::all();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/catalogo/typemessage",
     *      operationId="postTypeMessage",
     *      tags={"Type Message"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo tipo de masaje en el sistema.",
     *      description="Retorna el objeto del tipo de masaje creado.",
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
            TypeMessage::create($request->all());
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/catalogo/typemessage/{typemessage}",
     *      operationId="updateTypeMessage",
     *      tags={"Type Message"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el tipo de masaje seleccionado.",
     *      description="Retorna el objeto del tipo de masaje actualizado.",
     *      @OA\Parameter(
     *          description="ID del tipo de masaje para actualizar",
     *          in="path",
     *          name="typemessage",
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
    public function update(Request $request, TypeMessage $typemessage)
    {
        $this->validate($request, $this->rules($typemessage->id), $this->messages());

        try {
            $typemessage->name = $request->name;

            if (!$typemessage->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $typemessage->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/catalogo/typemessage/{typemessage}   ",
     *      operationId="deleteTypeMessage",
     *      tags={"Type Message"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el tipo de masaje seleccionado.",
     *      description="Retorna el objeto del tipo de masaje eliminado.",
     *      @OA\Parameter(
     *          description="ID del tipo de masaje para eliminar",
     *          in="path",
     *          name="typemessage",
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
    public function destroy($typemessage)
    {
        $typemessage = TypeMessage::withTrashed()->find($typemessage);
        if (is_null($typemessage->deleted_at)) {
            $typemessage->delete();
            $message = 'descativado';
        } else {
            $typemessage->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => is_null($id) ? 'required|max:50|unique:type_massages,name' : "required|max:50|unique:type_massages,name,{$id}"
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
