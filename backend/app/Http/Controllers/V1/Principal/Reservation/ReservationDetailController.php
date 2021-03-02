<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationOfert;
use App\Models\V1\Principal\ReservationDetail;

class ReservationDetailController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request, Reservation $reservationdetail)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            foreach ($request->details as $value) {
                $detail = ReservationDetail::create(
                    [
                        'price' => floatval($value['price']),
                        'ofert' => is_null($value['ofert']) ? false : true,
                        'reservation_id' => $reservationdetail->id,
                        'room_id' => $value['room_id'],
                        'coin_id' => $reservationdetail->coin_id
                    ]
                );

                if (!is_null($value['ofert'])) {
                    ReservationOfert::create(
                        [
                            'reservation_id' => $reservationdetail->id,
                            'reservation_detail_id' => $detail->id,
                            'ofert_room_id' => $value['ofert']
                        ]
                    );
                }

                $reservationdetail->total += $detail->price;
                $reservationdetail->save();
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\V1\Principal\ReservationDetail  $reservationDetail
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservationdetail)
    {
        $reservationdetail  = ReservationDetail::with('room')->where('reservation_id', $reservationdetail->id)->orderBy('id', 'ASC')->get();
        return $this->showAll($reservationdetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\V1\Principal\ReservationDetail  $reservationDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationDetail $reservationdetail)
    {
        try {

            DB::beginTransaction();
            $reservation = Reservation::find($reservationdetail->reservation_id);
            $reservation->total -= $reservationdetail->price;
            $reservation->save();

            ReservationOfert::where('reservation_detail_id', $reservationdetail->id)->forceDelete();
            $reservationdetail->forceDelete();
            DB::commit();

            return $this->successResponse('Registro anulado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }
}
