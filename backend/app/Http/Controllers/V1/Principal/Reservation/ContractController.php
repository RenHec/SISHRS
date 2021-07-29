<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Illuminate\Http\Request;
use App\Mail\paymentMethodEmail;
use App\Models\V1\Catalogo\Status;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Catalogo\WayToPay;
use Illuminate\Support\Facades\Mail;
use App\Models\V1\Principal\Contract;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\AdvancePrice;
use App\Models\V1\Principal\ReservationDetail;

class ContractController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }

    public function validarLink($link)
    {
        try {
            $contract = Contract::where('url', $link)->first();
            $way = WayToPay::where('advance', true)->get();
            $advance = AdvancePrice::where('contract_id', $contract->id)->first();

            if (is_null($contract)) {
                return $this->errorResponse('El link de validación es incorrecto.', 404);
            } else {
                $reservation = Reservation::with('coin', 'client')->find($contract->reservation_id);

                $detail = ReservationDetail::with('room.type_bed', 'room.type_room', 'room.type_service', 'room.pictures')->where('reservation_id', $contract->reservation_id)->get();

                if ($reservation->status_id == Status::CANCELACION) {
                    $reservation = null;
                    $detail = null;
                    $way = null;
                }
            }

            return response()->json(['contract' => $contract, 'reservation' => $reservation, 'detail' => $detail, 'way' => $way, 'advance' => $advance], 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }

    public function firmaContrato(Request $request, Contract $contract, Reservation $reservation)
    {
        try {
            $contract->answer = $request->answer;

            if ($request->answer == Contract::ACEPTO) {
                $img = $this->getB64Image($request->firm);
                $image = Image::make($img);
                $image->encode('png', 70);
                $year = date('Y');
                $date = date('YmdHis');
                $path = "{$year}/{$reservation->code}_{$date}.jpg";
                Storage::disk('firm')->put($path, $image);

                $contract->firm = $path;
            }

            $contract->save();

            return $this->successResponse('Gracias por preferir nuestras instalaciones.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }

    public function metodoPago(Request $request, Contract $contract, Reservation $reservation)
    {
        try {
            DB::beginTransaction();
            $insert = new AdvancePrice();
            $insert->id = $contract->id;
            $insert->amount = $request->pay == "COMPLETO" ? $reservation->total_reservation : ($reservation->total_reservation * 50) / 100;
            $insert->link = mb_strtolower($request->way_to_pay['name']) == 'link' ? route('bac_payment.paymentData', $contract->url) : null;
            $insert->document = null;
            $insert->authorization_link = null;
            $insert->payment_percentage = $request->pay;
            $insert->status = null;
            $insert->contract_id = $contract->id;
            $insert->reservation_id = $reservation->id;
            $insert->way_to_pay_id = $request->way_to_pay['id'];
            $insert->coin_id = $reservation->coin_id;
            $insert->pay = false;

            if (mb_strtolower($request->way_to_pay['name']) != 'link') {
                if (!is_null($request->document)) {
                    $img = $this->getB64Image($request->document);
                    $image = Image::make($img);
                    $image->encode('jpg', 70);
                    Storage::disk('advance_price_document')->put($contract->firm, $image);

                    $insert->document = $contract->firm;
                }
            }

            $insert->save();
            DB::commit();

            if ($insert->way_to_pay_id == 5) {
                Mail::to($reservation->client->email)->locale('es')->send(new paymentMethodEmail($reservation, $insert));
            }

            return $this->successResponse('Método de pago creado.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }

    public function verficiarLinkBoleta($link)
    {
        try {
            $contract = Contract::where('url', $link)->first();
            $advance = AdvancePrice::where('contract_id', $contract->id)->first();

            if (is_null($contract)) {
                return $this->errorResponse('El link de validación es incorrecto.', 404);
            }
            if (is_null($advance)) {
                return $this->errorResponse('No existe ningún método de pago para esta reservación.', 404);
            }

            return response()->json(['contract' => $link, 'advance' => $advance], 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }

    public function adjuntarBoleta(Request $request, Contract $contract, AdvancePrice $advance)
    {
        try {
            if (!is_null($request->document)) {
                $img = $this->getB64Image($request->document);
                $image = Image::make($img);
                $image->fit(870, 620, function ($constraint) {
                    $constraint->upsize();
                });
                $image->encode('jpg', 70);
                Storage::disk('advance_price_document')->put($contract->firm, $image);

                $advance->document = $contract->firm;
                $advance->save();
            } else {
                return $this->errorResponse('Es obligatorio adjuntar el documento.', 500);
            }

            return $this->successResponse('El documento fue presentado exitosamente, espere para su confirmación.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }
}
