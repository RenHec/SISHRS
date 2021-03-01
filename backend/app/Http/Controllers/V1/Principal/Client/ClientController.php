<?php

namespace App\Http\Controllers\V1\Principal\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Client;
use App\Models\V1\Catalogo\Municipio;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;
use App\Models\V1\Principal\ClientPhone;

class ClientController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/client",
     *      operationId="getClients",
     *      tags={"Client"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los clientes registrados en la base de datos.",
     *      description="Retorna un array de clientes.",
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
        $data = Client::with('municipality', 'phones')->get();;
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/client",
     *      operationId="postClient",
     *      tags={"Client"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo cliente en el sistema.",
     *      description="Retorna el objeto del cliente creado.",
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
            DB::beginTransaction();

            $data = $request->all();
            $data['municipality_id'] = $request->municipality_id['id'];
            $data['departament_id'] = Municipio::find($request->municipality_id['id'])->departament_id;

            $client = Client::create($data);

            foreach ($request->phones as $value) {
                ClientPhone::create(
                    [
                        'client_id' => $client->id,
                        'number' => $value['number'],
                        'area_code' => $value['area_code'],
                        'country' => $value['country'],
                        'url' => $value['url']
                    ]
                );
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/client/{client}",
     *      operationId="findClientbyId",
     *      tags={"Client"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todas las reservaciones realizadas por el cliente seleccionado.",
     *      description="Retorna un array de las reservaciones realizadas por el cliente seleccionado.",
     *      @OA\Parameter(
     *          description="ID del cliente a consultar",
     *          in="path",
     *          name="client",
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
    public function show(Client $client)
    {
        $client->reservations;
        return $this->showOne($client);
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/client/{client}",
     *      operationId="updateClient",
     *      tags={"Client"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el cliente seleccionado.",
     *      description="Retorna el objeto del cliente actualizado.",
     *      @OA\Parameter(
     *          description="ID del cliente para actualizar",
     *          in="path",
     *          name="client",
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
    public function update(Request $request, Client $client)
    {
        $this->validate($request, $this->rules($client->id), $this->messages());

        try {
            $client->nit = $request->nit;
            $client->first_name = $request->first_name;
            $client->second_name = $request->second_name;
            $client->surname = $request->surname;
            $client->second_surname = $request->second_surname;
            $client->email = $request->email;
            $client->ubication = $request->ubication;
            $client->municipality_id = $request->municipality_id['id'];
            $client->departament_id = Municipio::find($request->municipality_id['id'])->departament_id;

            if(!$client->isDirty())
                return $this->errorResponse('No hay datos para actualizar', 423);
            
            $client->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/client/{client}",
     *      operationId="deleteClient",
     *      tags={"Client"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el cliente seleccionado.",
     *      description="Retorna el objeto del cliente eliminado.",
     *      @OA\Parameter(
     *          description="ID del cliente para eliminar",
     *          in="path",
     *          name="client",
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
    public function destroy(Client $client)
    {
        try {
            $client->forceDelete();
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
        $validar = is_null($id) ? [
            'nit' => is_null($id) ? 'required|numeric|digits_between:5,15|unique:clients,nit' : "required|numeric|digits_between:13,15|unique:clients,nit,{$id}",
            'first_name' => 'required|max:50',
            'second_name' => 'nullable|max:50',
            'surname' => 'required|max:50',
            'second_surname' => 'nullable|max:50',
            'email' => is_null($id) ? 'required|email|max:75|unique:clients,email' : "required|email|max:75|unique:clients,email,{$id}",
            'ubication' => 'nullable|max:100',
            'municipality_id.id' => 'required|integer|exists:municipalities,id',

            'phones.*.number' => 'required|max:15',
            'phones.*.area_code' => 'required|max:10',
            'phones.*.country' => 'required|max:75',
            'phones.*.url' => 'required|max:100'
        ] : [
            'nit' => is_null($id) ? 'required|numeric|digits_between:5,15|unique:clients,nit' : "required|numeric|digits_between:5,15|unique:clients,nit,{$id}",
            'first_name' => 'required|max:50',
            'second_name' => 'nullable|max:50',
            'surname' => 'required|max:50',
            'second_surname' => 'nullable|max:50',
            'email' => is_null($id) ? 'required|email|max:75|unique:clients,email' : "required|email|max:75|unique:clients,email,{$id}",
            'ubication' => 'nullable|max:100',
            'municipality_id.id' => 'required|integer|exists:municipalities,id'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'nit.required' => 'El número de NIT es obligatorio.',
            'nit.numeric' => 'El número de NIT tiene formato incorrecto.',
            'nit.digits_between'  => 'El número de NIT ingresado no tiene un mínimo de :min y un máximo de :max dígitos.',
            'nit.unique'  => 'El número de NIT ingreado ya existe en el sistema.',

            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_name.max'  => 'El primer nombre debe tener menos de :max carácteres.',

            'second_name.max'  => 'El segundo nombre debe tener menos de :max carácteres.',

            'surname.required' => 'El primer apellido es obligatorio.',
            'surname.max'  => 'El primer apellido debe tener menos de :max carácteres.',

            'second_surname.max'  => 'El segundo apellido debe tener menos de :max carácteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El dato ingresado no es un correo electrónico.',
            'email.max'  => 'El correo electrónico debe tener menos de :max caracteres.',
            'email.unique'  => 'El correo electrónico ingresado ya existe en el sistema.',

            'ubication.max'  => 'La ubicación de de tener menos de :max carácteres.',

            'municipality_id.id.required' => 'El departamento y muicipalidad es obligatorio',
            'municipality_id.id.integer' => 'El departamento y muicipalidad no es un número',
            'municipality_id.id.exists' => 'El departamento y muicipalidad no existe en la base de datos',

            'phones.*.number.required' => 'El número de teléfono es obligatorio.',
            'phones.*.number.max' => 'El número de teléfono debe tener menos de :max carácteres.',

            'phones.*.area_code.required' => 'El área del número de teléfono es obligatorio.',
            'phones.*.area_code.max' => 'El área del número de teléfono debe tener menos de :max carácteres.',

            'phones.*.country.required' => 'El país del número de teléfono es obligatorio.',
            'phones.*.country.max' => 'El país del número de teléfono debe tener menos de :max carácteres.',

            'phones.*.url.required' => 'La URL del número de teléfono es obligatorio.',
            'phones.*.url.max' => 'La URL del número de teléfono debe tener menos de :max carácteres.'
        ];
    }
}
