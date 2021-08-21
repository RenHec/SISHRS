<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\Status;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\TypeService;
use App\Models\V1\Principal\ReservationDetail;
use App\Models\V1\Principal\ReservationService;
use App\Models\V1\Principal\BinnacleReservation;
use App\Models\V1\Principal\Reservation;

class ReservationServiceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = ReservationService::with('client.phones', 'room.type_room', 'room.type_bed', 'user', 'type_service', 'reservations_detail')->where('user_id', Auth::user()->id)->where('end_time', null)->get();
        return $this->showAll($data);
    }

    public function call_services(TypeService $type_service)
    {
        $data = ReservationDetail::with('client.phones', 'status', 'room.type_room', 'room.type_bed')
            ->where('status_id', '=', Status::EN_PROCESO)
            ->where('asign', false)
            ->where('type_service_id', '=', $type_service->id)->get();
        $asignados = ReservationService::with('client.phones', 'room.type_room', 'room.type_bed', 'user', 'reservations_detail')->get();
        $users = Usuario::where('id', '!=', Auth::user()->id)->get();
        $users = Usuario::all();
        return $this->successResponse(['data' => $data, 'users' => $users, 'asignados' => $asignados]);
    }

    public function store(Request $request)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $deatil = ReservationDetail::find($request->reservations_detail_id);
            $reservation = Reservation::find($deatil->reservation_id);

            ReservationService::create([
                'start_date' => null,
                'end_date' => null,
                'start_time' => null,
                'end_time' => null,
                'duration' => null,
                'percentage' => $request->percentage,
                'commission' => $request->percentage > 0 ? ($request->percentage * $deatil->sub) / 100 : 0,
                'description' => null,
                'reservations_detail_id' => $deatil->id,
                'reservation_id' => $reservation->id,
                'user_id' => $request->user_id['id'],
                'room_id' => $deatil->room_id,
                'client_id' => $deatil->client_id,
                'type_service_id' => $deatil->type_service_id
            ]);

            $deatil->asign = true;
            $deatil->status_id = $reservation->status_id;
            $deatil->save();

            BinnacleReservation::where('reservation_detail_id', $deatil->id)
                ->update(
                    [
                        'active' => false
                    ]
                );

            BinnacleReservation::create(
                [
                    'start' => $deatil->arrival_date,
                    'end' => $deatil->departure_date,
                    'days' => 60,
                    'reservation_detail_id' => $deatil->id,
                    'movement_id' => Movement::ASIGNADA,
                    'user_id' => Auth::user()->id,
                    'type_service_id' => $deatil->type_service_id
                ]
            );

            DB::commit();

            return $this->successResponse('Servicio asignado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    public function start(ReservationService $reservation_service)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $deatil = ReservationDetail::find($reservation_service->reservations_detail_id);

            $reservation_service->start_date = date('Y-m-d');
            $reservation_service->end_date = null;
            $reservation_service->start_time = date('H:i:s');
            $reservation_service->end_time = null;
            $reservation_service->duration = null;
            $reservation_service->save();

            BinnacleReservation::where('reservation_detail_id', $deatil->id)
                ->update(
                    [
                        'active' => false
                    ]
                );

            BinnacleReservation::create(
                [
                    'start' => $deatil->arrival_date,
                    'end' => $deatil->departure_date,
                    'days' => 60,
                    'reservation_detail_id' => $deatil->id,
                    'movement_id' => Movement::INICIADO,
                    'user_id' => Auth::user()->id,
                    'type_service_id' => $deatil->type_service_id
                ]
            );

            DB::commit();

            return $this->successResponse('Servicio iniciado');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    public function end(ReservationService $reservation_service)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $deatil = ReservationDetail::find($reservation_service->reservations_detail_id);

            $reservation_service->end_date = date('Y-m-d');
            $reservation_service->end_time = date('H:i:s');

            $fecha1 = strtotime("{$reservation_service->start_date} {$reservation_service->start_time}");
            $fecha2 = strtotime("{$reservation_service->end_date} {$reservation_service->end_time}");

            $diferencia_segundos = ($fecha2 - $fecha1);
            $diferencia_minutos = ($fecha2 - $fecha1) / 60;
            $diferencia_horas = ($fecha2 - $fecha1) / 60 / 60;

            $reservation_service->duration = date("H:i:s", strtotime("{$reservation_service->end_date} {$diferencia_horas}:{$diferencia_minutos}:{$diferencia_segundos}"));
            $reservation_service->save();

            BinnacleReservation::where('reservation_detail_id', $deatil->id)
                ->update(
                    [
                        'active' => false
                    ]
                );

            BinnacleReservation::create(
                [
                    'start' => $deatil->arrival_date,
                    'end' => $deatil->departure_date,
                    'days' => 60,
                    'reservation_detail_id' => $deatil->id,
                    'movement_id' => Movement::INICIADO,
                    'user_id' => Auth::user()->id,
                    'type_service_id' => $deatil->type_service_id
                ]
            );

            DB::commit();

            return $this->successResponse('Servicio iniciado');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }
}
