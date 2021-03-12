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
        $data = TypeMessage::with('type_service', 'coin')->withTrashed()->get();
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
            $data = $request->all();
            $data['coin_id'] = $request->coin_id['id'];
            $data['type_service_id'] = $request->type_service_id['id'];
            $data['price'] = str_replace(',','', $request->price);
            
            TypeMessage::create($data);
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
    public function update(Request $request, TypeMessage $type_message)
    {
        $this->validate($request, $this->rules($type_message->id), $this->messages());

        try {
            $type_message->time = $request->time;
            $type_message->name = $request->name;
            $type_message->price = str_replace(',','', $request->price);
            $type_message->coin_id = $request->coin_id['id'];
            $type_message->type_service_id = $request->type_service_id['id'];

            if (!$type_message->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $type_message->save();

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
    public function destroy($type_message)
    {
        $type_message = TypeMessage::withTrashed()->find($type_message);
        if (is_null($type_message->deleted_at)) {
            $type_message->delete();
            $message = 'descativado';
        } else {
            $type_message->restore();
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
