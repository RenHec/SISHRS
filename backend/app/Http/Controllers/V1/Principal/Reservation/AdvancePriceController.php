<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\AdvancePrice;
use App\Models\V1\Principal\Reservation;
use Illuminate\Support\Facades\DB;

class AdvancePriceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = AdvancePrice::with('contract', 'reservation', 'way_to_pay')->get();
        return $this->showAll($data);
    }

    public function show(AdvancePrice $advance_price)
    {
        try {
            DB::beginTransaction();

            $advance_price->pay = $advance_price->pay ? false : true;
            $advance_price->save();

            $reservation = Reservation::find($advance_price->reservation_id);
            $reservation->advance_price = $advance_price->pay ? $advance_price->amount : 0;
            $reservation->save();

            DB::commit();
            return $this->successResponse('El estado del pago fue actualizado.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }

    public function update(Request $request, AdvancePrice $advance_price)
    {
        try {
            if (!is_null($request->document)) {
                $img = $this->getB64Image($request->document);
                $image = Image::make($img);
                $image->encode('jpg', 70);
                Storage::disk('advance_price_document')->put($advance_price->contract->firm, $image);

                $advance_price->document = $advance_price->contract->firm;
                $advance_price->save();
            } else {
                return $this->errorResponse('Es obligatorio adjuntar el documento.', 500);
            }

            return $this->successResponse('El documento fue presentado exitosamente, espere para su confirmación.');
        } catch (\Throwable $th) {
            return $this->errorResponse('Ocurrio un problema.', 500);
        }
    }
}
