<?php

namespace App\Http\Controllers\V1\Principal\Room;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\OfertRoom;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class OfertRoomController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/ofert_room",
     *      operationId="getOfertsRooms",
     *      tags={"Ofert Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todas las ofertas de las habitaciones registradas en la base de datos.",
     *      description="Retorna un array de ofertas.",
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
        $data = OfertRoom::with('room', 'coin')->get();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/ofert_room",
     *      operationId="postOfertRoom",
     *      tags={"Ofert Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear una nueva oferta de la habitación en el sistema.",
     *      description="Retorna el objeto de la oferta creada.",
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

            foreach ($request->room_id as $value) {
                OfertRoom::create(
                    [
                        'accommodation' => $request->accommodation,
                        'price' => floatval($request->price),
                        'observation' => $request->observation,
                        'start_date' => date('Y-m-d h:i:s', strtotime($request->start_date)),
                        'end_date' => date('Y-m-d h:i:s', strtotime($request->end_date)),
                        'active' => $request->active,
                        'room_id' => $value['id'],
                        'coin_id' => $request->coin_id['id']
                    ]
                );
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/ofert_room/{ofert_room}",
     *      operationId="updateOfertRoom",
     *      tags={"Ofert Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar la oferta seleccionada.",
     *      description="Retorna el objeto de la oferta actualizada.",
     *      @OA\Parameter(
     *          description="ID de la oferta para actualizar",
     *          in="path",
     *          name="ofert_room",
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
    public function update(Request $request, OfertRoom $ofertroom)
    {
        $this->validate($request, $this->rules($ofertroom->id), $this->messages());

        try {
            $ofertroom->accommodation = $request->accommodation;
            $ofertroom->price = floatval($request->price);
            $ofertroom->observation = $request->observation;
            $ofertroom->start_date = date('Y-m-d h:i:s', strtotime($request->start_date));
            $ofertroom->end_date = date('Y-m-d h:i:s', strtotime($request->end_date));
            $ofertroom->active = $request->active;
            $ofertroom->room_id = $request->room_id['id'];
            $ofertroom->coin_id = $request->coin_id['id'];

            if (!$ofertroom->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $ofertroom->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/ofert_room/{ofert_room}",
     *      operationId="deleteOfertRoom",
     *      tags={"Ofert Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar la oferta seleccionada.",
     *      description="Retorna el objeto de la oferta eliminada.",
     *      @OA\Parameter(
     *          description="ID de la oferta para eliminar.",
     *          in="path",
     *          name="ofert_room",
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
    public function destroy(OfertRoom $ofertroom)
    {
        try {
            $ofertroom->forceDelete();
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
            'accommodation' => 'required|between:1,60',
            'price' => 'required|between:1,9999999',
            'observation' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'active' => 'required',
            'coin_id.id' => 'required|integer|exists:coins,id',

            'room_id.*.id' => 'required|integer|exists:rooms,id'
        ] : [
            'accommodation' => 'required|between:1,60',
            'price' => 'required|between:1,9999999',
            'observation' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'active' => 'required',
            'coin_id.id' => 'required|integer|exists:coins,id',
            'room_id.id' => 'required|integer|exists:rooms,id'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'accommodation.required' => 'La cantidad de días para la estancia es obligatorio.',
            'accommodation.between' => 'La cantidad de días para la estancia tiene que estar en un rango entre :min y :max.',

            'price.required' => 'El precio de la habitación es obligatorio.',
            'price.between' => 'El precio de la habitación tiene que estar en un rango entre :min y :max.',

            'observation.required' => 'La descripción de la oferta es obligatoria.',

            'start_date.required' => 'La fecha de inicio de la oferta es obligatoria.',
            'start_date.date_format' => 'La fecha de inicio de la oferta no tiene formato correcto.',

            'end_date.required' => 'La fecha de finalización de la oferta es obligatoria.',
            'end_date.date_format' => 'La fecha de finalización de la oferta no tiene formato correcto.',

            'active.required' => 'Es necesario que indique si la oferta será publicada.',

            'coin_id.id.required' => 'El tipo de moneda es obligatorio.',
            'coin_id.id.integer' => 'El tipo de moneda no es un número.',
            'coin_id.id.exists' => 'El tipo de moneda no existe en la base de datos.',

            'room_id.id.required' => 'La habitación es obligatoria.',
            'room_id.id.integer' => 'La habitación no es un número.',
            'room_id.id.exists' => 'La habitación no existe en la base de datos.',

            'room_id.*.id.required' => 'La habitación es obligatoria.',
            'room_id.*.id.integer' => 'La habitación no es un número.',
            'room_id.*.id.exists' => 'La habitación no existe en la base de datos.'
        ];
    }
}
