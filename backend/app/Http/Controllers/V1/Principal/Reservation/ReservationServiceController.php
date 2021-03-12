<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationService;

class ReservationServiceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update(Request $request, Reservation $reservationservice)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $service = ReservationService::create([
                'price' => floatval($request->price),
                'description' => $request->description,
                'reservation_id' => $reservationservice->id,
                'coin_id' => $reservationservice->coin_id
            ]);

            $reservationservice->total += floatval($service->price);
            $reservationservice->save();

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    public function show(Reservation $reservationservice)
    {
        $reservationservice  = ReservationService::with('room')->where('reservation_id', $reservationservice->id)->orderBy('id', 'ASC')->get();
        return $this->showAll($reservationservice);
    }


    public function destroy(ReservationService $reservationservice)
    {
        try {

            DB::beginTransaction();
            $reservation = Reservation::find($reservationservice->reservation_id);
            $reservation->total -= floatval($reservationservice->price);
            $reservation->save();

            $reservationservice->forceDelete();
            DB::commit();

            return $this->successResponse('Registro anulado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }
}
