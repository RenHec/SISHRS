<?php

namespace App\Http\Controllers\V1\Principal\Client;

use Illuminate\Http\Request;
use App\Models\V1\Principal\Client;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;
use App\Models\V1\Principal\ClientPhone;

class ClientPhoneController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/client_phone/{client_phone}",
     *      operationId="findClientPhonebyId",
     *      tags={"Client Phone"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los números de teléfono del cliente seleecionado.",
     *      description="Retorna un array de los números de teléfono del cliente seleccionado.",
     *      @OA\Parameter(
     *          description="ID del cliente a consultar",
     *          in="path",
     *          name="client_phone",
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
    public function show(Client $client_phone)
    {
        $client_phone->phones;
        return $this->showOne($client_phone);
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/client_phone/{client_phone}",
     *      operationId="updateClientPhone",
     *      tags={"Client Phone"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Agregar un nuevo número de teléfono al cliente seleccionado.",
     *      description="Retorna el objeto del cliente actualizado.",
     *      @OA\Parameter(
     *          description="ID del cliente para agregar un nuevo número de teléfono",
     *          in="path",
     *          name="client_phone",
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
    public function update(Request $request, Client $client_phone)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {

            ClientPhone::create(
                [
                    'client_id' => $client_phone->id,
                    'number' => $request->number,
                    'area_code' => $request->area_code,
                    'country' => $request->country,
                    'url' => $request->url
                ]
            );

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/client_phone/{client_phone}",
     *      operationId="deleteClientPhone",
     *      tags={"Client Phone"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el número de teléfono seleccionado.",
     *      description="Retorna el objeto del número de teléfono eliminado.",
     *      @OA\Parameter(
     *          description="ID del número de teléfono para eliminar",
     *          in="path",
     *          name="client_phone",
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
    public function destroy(ClientPhone $client_phone)
    {
        try {
            $client_phone->forceDelete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }

    //Reglas de validaciones
    public function rules()
    {
        $validar = [
            'number' => 'required|max:15',
            'area_code' => 'required|max:10',
            'country' => 'required|max:75',
            'url' => 'required|max:100'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'number.required' => 'El número de teléfono es obligatorio.',
            'number.max' => 'El número de teléfono debe tener menos de :max carácteres.',

            'area_code.required' => 'El área del número de teléfono es obligatorio.',
            'area_code.max' => 'El área del número de teléfono debe tener menos de :max carácteres.',

            'country.required' => 'El país del número de teléfono es obligatorio.',
            'country.max' => 'El país del número de teléfono debe tener menos de :max carácteres.',

            'url.required' => 'La URL del número de teléfono es obligatorio.',
            'url.max' => 'La URL del número de teléfono debe tener menos de :max carácteres.'
        ];
    }
}
