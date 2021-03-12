<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\V1\Principal\Room;
use App\Models\V1\Catalogo\Status;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Client;
use App\Models\V1\Catalogo\Movement;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\PictureRoom;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationOfert;
use App\Models\V1\Principal\ReservationDetail;
use App\Models\V1\Principal\BinnacleReservation;

class ReservationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = Reservation::with('client.phones', 'user', 'status')->where('status_id', '!=', Status::ANULADO)->get();
        return $this->showAll($data);
    }

    public function pendiente()
    {
        $data = DB::table('reservations')
            ->join('clients', 'reservations.client_id', 'clients.id')
            ->join('coins', 'reservations.coin_id', 'coins.id')
            ->select(
                'reservations.id AS id',
                DB::RAW('CONCAT(reservations.code," | ",clients.first_name," ",clients.surname) AS name'),
                DB::RAW('CONCAT(coins.symbol," ",FORMAT(reservations.total,2)) AS total')
            )
            ->where('reservations.status_id', Status::PENDIENTE)
            ->get();

        return $this->showAll($data);
    }

    public function promocion(Room $room)
    {
        $data = DB::table('oferts_rooms')
            ->join('coins', 'oferts_rooms.coin_id', 'coins.id')
            ->select(
                'oferts_rooms.id AS id',
                'oferts_rooms.price AS sf_price',
                DB::RAW('CONCAT(oferts_rooms.accommodation," noches por: ",coins.symbol," ",FORMAT(oferts_rooms.price,2)) AS name')
            )
            ->where('oferts_rooms.room_id', $room->id)
            ->where('active', true)
            ->get();

        return $this->showAll($data);
    }

    public function calendario()
    {
        $data = ReservationDetail::with('reservation.client.phones')->whereIn('status_id', [Status::CONFIRMADO, Status::EN_PROCESO])->get();
        return $this->showAll($data);
    }

    public function buscar_habitaciones(Request $request)
    {
        $servicios = $request->servicios == 'null' || is_null($request->servicios) ? null : $request->servicios['id'];
        $inicio = $request->inicio == 'null' || is_null($request->inicio) ? null : $request->inicio;
        $fin = $request->fin == 'null' || is_null($request->fin) ? null : $request->fin;
        $hora = $request->hora == 'null' || is_null($request->hora) ? null : $request->hora;
        $cantidad = $request->cantidad == 'null' || is_null($request->cantidad) ? null : $request->cantidad;

        $data = DB::table('rooms')
            ->join('type_beds', 'rooms.type_bed_id', 'type_beds.id')
            ->join('type_rooms', 'rooms.type_room_id', 'type_rooms.id')
            ->join('coins', 'rooms.coin_id', 'coins.id')
            ->join('type_services', 'rooms.type_service_id', 'type_services.id')
            ->select(
                'rooms.id AS id',
                DB::RAW('CONCAT(rooms.number," ",type_services.name," - ",rooms.name) AS name'),
                'rooms.amount_people AS amount_people',
                'type_rooms.name AS type_room',
                DB::RAW('CONCAT(rooms.amount_bed," ",type_beds.name) AS type_bed'),
                DB::RAW('CONCAT(coins.symbol," ",FORMAT(rooms.price,2)) AS price'),
                'rooms.price AS sf_price',
                'rooms.description AS description',
                'coins.id AS coin_id',
                'rooms.type_service_id AS type_service_id'
            )
            ->when($inicio && $fin && !$hora, function ($query) use ($inicio, $fin) {
                $query->whereNotExists(function ($subquery) use ($inicio, $fin) {
                    $subquery->select(DB::raw(1))
                        ->from('reservations_details')
                        ->whereIn('reservations_details.status_id', [Status::PENDIENTE, Status::EN_PROCESO, Status::CONFIRMADO])
                        ->whereBetween(DB::RAW('DATE_FORMAT(reservations_details.departure_date, "%Y-%m-%d")'), [$inicio, $fin])
                        ->whereRaw('reservations_details.room_id = rooms.id');
                });
            })
            ->when($inicio && $fin && $hora, function ($query) use ($inicio, $hora) {
                $query->whereNotExists(function ($subquery) use ($inicio, $hora) {
                    $subquery->select(DB::raw(1))
                        ->from('reservations_details')
                        ->whereIn('reservations_details.status_id', [Status::PENDIENTE, Status::EN_PROCESO, Status::CONFIRMADO])
                        ->where(DB::RAW('DATE_FORMAT(reservations_details.departure_date, "%Y-%m-%d %h:%i")'), "$inicio $hora")
                        ->whereRaw('reservations_details.room_id = rooms.id');
                });
            })
            ->when($servicios, function ($query) use ($servicios) {
                $query->where('rooms.type_service_id', $servicios);
            })
            ->when($cantidad, function ($query) use ($cantidad) {
                $query->where(DB::RAW('rooms.amount_people - rooms.resta'), '>', $cantidad - 1);
            })
            ->whereNull('rooms.deleted_at')
            ->get();

        $array = array();
        $array_ids = array();

        foreach ($data as $value) {
            $photo = PictureRoom::where('room_id', $value->id)->orderBy('position', 'ASC')->first();

            $info['id'] = $value->id;
            $info['name'] = $value->name;
            $info['amount_people'] = $value->amount_people;
            $info['type_room'] = $value->type_room;
            $info['type_bed'] = $value->type_bed;
            $info['price'] = $value->price;
            $info['sf_price'] = $value->sf_price;
            $info['description'] = $value->description;
            $info['coin_id'] = $value->coin_id;
            $info['type_service_id'] = $value->type_service_id;
            $info['photo'] = is_null($photo) ? null : $photo->picture;
            $info['esconder'] = false;

            array_push($array, $info);
            array_push($array_ids, $value->id);
        }

        $precios = DB::table('rooms_prices')
            ->join('rooms', 'rooms_prices.room_id', 'rooms.id')
            ->join('coins', 'rooms.coin_id', 'coins.id')
            ->join('type_charge', 'rooms_prices.type_charge_id', 'type_charge.id')
            ->select(
                'rooms.id AS id',
                DB::RAW('CONCAT(type_charge.name," - ",coins.symbol," ",FORMAT(rooms_prices.price,2)) AS name'),
                'rooms_prices.price AS sf_price'
            )
            ->whereIn('rooms_prices.room_id', $array_ids)
            ->get();

        $masajes = DB::table('rooms_massages')
            ->join('type_massages', 'rooms_massages.type_massage_id', 'type_massages.id')
            ->join('coins', 'type_massages.coin_id', 'coins.id')
            ->select(
                'rooms_massages.room_id AS id',
                DB::RAW('CONCAT(type_massages.name," | Precio: ",coins.symbol," ",FORMAT(type_massages.price,2)," | Tiempo: ",type_massages.time," min.") AS name'),
                'type_massages.price AS sf_price'
            )
            ->whereIn('rooms_massages.room_id', $array_ids)
            ->get();

        return $this->successResponse(['habitaciones' => $array, 'precios' => $precios, 'masajes' => $masajes]);
    }

    public function buscar_habitacion(Room $room, $inicio, $fin)
    {
        $data = DB::table('rooms')
            ->join('type_beds', 'rooms.type_bed_id', 'type_beds.id')
            ->join('type_rooms', 'rooms.type_room_id', 'type_rooms.id')
            ->join('coins', 'rooms.coin_id', 'coins.id')
            ->join('type_services', 'rooms.type_service_id', 'type_services.id')
            ->select(
                'rooms.id AS id',
                DB::RAW('CONCAT(rooms.number," ",type_services.name," - ",rooms.name) AS name'),
                'rooms.amount_people AS amount_people',
                'type_rooms.name AS type_room',
                DB::RAW('CONCAT(rooms.amount_bed," ",type_beds.name) AS type_bed'),
                DB::RAW('CONCAT(coins.symbol," ",FORMAT(rooms.price,2)) AS price'),
                'rooms.price AS sf_price',
                'rooms.description AS description',
                'coins.id AS coin_id'
            )
            ->whereNotExists(function ($query) use ($inicio, $fin) {
                $query->select(DB::raw(1))
                    ->from('reservations')
                    ->join('reservations_details', 'reservations_details.reservation_id', 'reservations.id')
                    ->whereIn('reservations.status_id', [Status::PENDIENTE, Status::EN_PROCESO, Status::CONFIRMADO])
                    ->whereBetween(DB::RAW('DATE_FORMAT(reservations.departure_date, "%Y-%m-%d")'), [$inicio, $fin])
                    ->whereRaw('reservations_details.room_id = rooms.id');
            })
            ->where('rooms.id', $room->id)
            ->whereNull('rooms.deleted_at')
            ->get();

        $mensaje = count($data) > 0 ? "Si puede reservar." : "No, no puede reservar";
        $error = count($data) > 0 ? true : false;

        return $this->successResponse(['mensaje' => $mensaje, 'error' => $error]);
    }

    public function precios(Room $room)
    {
        $data = DB::table('rooms_prices')
            ->join('rooms', 'rooms_prices.room_id', 'rooms.id')
            ->join('coins', 'rooms.coin_id', 'coins.id')
            ->join('type_charge', 'rooms_prices.type_charge_id', 'type_charge.id')
            ->select(
                'rooms_prices.id AS rooms_prices',
                DB::RAW('CONCAT(type_charge.name," - ",coins.symbol," ",FORMAT(rooms_prices.price,2)) AS name'),
                'rooms_prices.price AS sf_price'
            )
            ->where('rooms_prices.room_id', $room->id)
            ->get();

        return $this->successResponse($data);
    }

    public function store(Request $request)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $generar = Reservation::whereYear('created_at', date('Y'))->count();
            $generar += 1;
            $user = Auth::user()->id;

            $data = $request->all();
            $data['code'] = $this->generadorCodigo($generar);
            $data['total'] = 0;
            $data['client_id'] = $request->client_id['id'];
            $data['user_id'] = $user;
            $data['status_id'] = Status::CONFIRMADO;
            $data['coin_id'] = $request->coin_id;

            $reservation = Reservation::create($data);
            $multiplicar = !is_null($request->cantidad) ? $request->cantidad : 1;

            $start = is_null($request->hora) ? Carbon::createFromFormat('Y-m-d', $request->arrival_date) : date('Y-m-d h:i:s', strtotime($request->arrival_date . ' ' . $request->hora));
            $end = is_null($request->hora) ? Carbon::createFromFormat('Y-m-d', $request->departure_date) : date('Y-m-d h:i:s', strtotime($request->departure_date . ' ' . $request->hora));
            $accommodation = is_null($request->hora) ? $start->diffInDays($end) : 0;

            foreach ($request->details as $value) {

                $room = Room::find($value['room_id']);

                $detail = ReservationDetail::create(
                    [
                        'price' => floatval($value['price']),
                        'ofert' => is_null($value['ofert']) ? false : true,
                        'reservation_id' => $reservation->id,
                        'room_id' => $value['room_id'],
                        'coin_id' => $reservation->coin_id,
                        'sub' => intval($accommodation) == 0 ? $multiplicar * floatval($value['price']) : intval($accommodation) * floatval($value['price']),
                        'arrival_date' => $start,
                        'departure_date' => $end,
                        'accommodation' => $accommodation,
                        'description' => $value['description'],
                        'type_service_id' => $room->type_service_id,
                        'status_id' => Status::CONFIRMADO
                    ]
                );

                $room->resta += $multiplicar;
                if(!is_null($request->cantidad)) {
                    $room->save();
                }

                if (!is_null($value['ofert'])) {
                    ReservationOfert::create(
                        [
                            'reservation_id' => $reservation->id,
                            'reservation_detail_id' => $detail->id,
                            'ofert_room_id' => $value['ofert']
                        ]
                    );
                }

                BinnacleReservation::create(
                    [
                        'start' => $detail->arrival_date,
                        'end' => $detail->departure_date,
                        'days' => 60,
                        'reservation_detail_id' => $detail->id,
                        'movement_id' => Movement::PROGRAMADA,
                        'user_id' => $user,
                        'type_service_id' => $detail->type_service_id
                    ]
                );

                $reservation->total += floatval($detail->sub);
            }

            if ($reservation->total > 0)
                $reservation->save();

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    public function show(ReservationDetail $reservation)
    {
        $data = ReservationDetail::with('room', 'coin', 'reservation.client.phones', 'status', 'type_service')->where('id', $reservation->id)->get();
        return $this->showAll($data);
    }

    public function update(Request $request, Reservation $reservation)
    {
        //$this->validate($request, $this->rules($reservation->id), $this->messages());

        try {
            $reservation->nit = $request->nit;
            $reservation->name = $request->name;
            $reservation->ubication = $request->ubication;

            if (!$reservation->isDirty())
                return $this->errorResponse('No hay datos para actualizar', 423);

            $reservation->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    public function destroy(Reservation $reservation)
    {
        try {
            $reservation->status_id = Status::ANULADO;
            $reservation->save();

            return $this->successResponse('Registro anulado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }
}
