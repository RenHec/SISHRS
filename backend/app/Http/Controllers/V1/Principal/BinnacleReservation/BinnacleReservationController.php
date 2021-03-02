<?php

namespace App\Http\Controllers\V1\Principal\BinnacleReservation;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Catalogo\Status;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\BinnacleReservation;
use Illuminate\Support\Facades\Auth;

class BinnacleReservationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/binnacle_reservation/{binnacle_reservation}",
     *      operationId="findBinnacleReservationbyId",
     *      tags={"Binnacle Reservation"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los movimientos realizados en la reservacion seleccionada.",
     *      description="Retorna un array de los movimientos realizados en la reservacion seleccionada.",
     *      @OA\Parameter(
     *          description="ID de la reservación a consultar",
     *          in="path",
     *          name="binnacle_reservation",
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
    public function show(Reservation $binnacle_reservation)
    {
        $binnacle_reservation->binnacle;
        return $this->showOne($binnacle_reservation);
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/binnacle_reservation/{binnacle_reservation}",
     *      operationId="updateBinnacleReservation",
     *      tags={"Binnacle Reservation"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar la fecha de reubicación de la reservación seleccionada.",
     *      description="Retorna el objeto de la reubicación de fecha en la reservación.",
     *      @OA\Parameter(
     *          description="ID de la reservación para actualizar la fecha de reubicación.",
     *          in="path",
     *          name="binnacle_reservation",
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
    public function update(Request $request, Reservation $binnacle_reservation)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();
            $start = Carbon::createFromFormat('Y-m-d', $request->arrival_date);
            $end = Carbon::createFromFormat('Y-m-d', $request->departure_date);

            $binnacle_reservation->arrival_date = date('Y-m-d h:i:s', strtotime($request->arrival_date));
            $binnacle_reservation->departure_date = date('Y-m-d h:i:s', strtotime($request->departure_date));
            $binnacle_reservation->accommodation = $start->diffInDays($end);
            $binnacle_reservation->save();

            $ultimo_movimiento = BinnacleReservation::where('reservation_id', $binnacle_reservation->id)->get()->last();
            $ultimo_movimiento->active = false;
            $ultimo_movimiento->save();

            BinnacleReservation::create(
                [
                    'start' => date('Y-m-d', strtotime($request->arrival_date)),
                    'end' => date('Y-m-d', strtotime($request->departure_date)),
                    'days' => $ultimo_movimiento->days,
                    'reservation_id' => $binnacle_reservation->id,
                    'movement_id' => Movement::REUBICADA,
                    'user_id' => Auth::user()->id
                ]
            );

            DB::commit();

            return $this->successResponse('Reservación reubicada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/binnacle_reservation/{binnacle_reservation}",
     *      operationId="deleteBinnacleReservation",
     *      tags={"Binnacle Reservation"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Cancelación de la reservación por parate del cliente.",
     *      description="Retorna la reservación cancelada.",
     *      @OA\Parameter(
     *          description="ID de la reservación a cancelar por el cliente.",
     *          in="path",
     *          name="binnacle_reservation",
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
    public function destroy(Reservation $binnacle_reservation)
    {
        try {
            DB::beginTransaction();
            $binnacle_reservation->status_id = Status::CANCELACION;
            $binnacle_reservation->save();

            BinnacleReservation::where('reservation_id', $binnacle_reservation->id)
                ->update(
                    [
                        'active' => false
                    ]
                );

            BinnacleReservation::create(
                [
                    'start' => date('Y-m-d'),
                    'end' => date('Y-m-d'),
                    'days' => 0,
                    'subtraction' => 0,
                    'reservation_id' => $binnacle_reservation->id,
                    'movement_id' => Movement::CANCELADA,
                    'user_id' => Auth::user()->id
                ]
            );

            DB::commit();

            return $this->successResponse('Reservación reubicada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    //Reglas de validaciones
    public function rules()
    {
        $validar = [
            'arrival_date' => 'required|date_format:Y-m-d',
            'departure_date' => 'required|date_format:Y-m-d|after:arrival_date'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'arrival_date.required' => 'La fecha de inicio es obligatoria',
            'arrival_date.date_format' => 'La fecha de inicio no tiene formato válido, Ejemplo: d-m-Y h:i:s',

            'departure_date.required' => 'La fecha de finalización es obligatoria',
            'departure_date.date_format' => 'La fecha de finalización no tiene formato válido, Ejemplo: d-m-Y h:i:s',
            'departure_date.after' => 'La fecha de finalización tine que ser mayor a :attribute',
        ];
    }
}
