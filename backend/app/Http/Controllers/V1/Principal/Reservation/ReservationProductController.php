<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\V1\Principal\Sale;
use App\Models\V1\Catalogo\Status;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Client;
use App\Models\V1\Principal\Kardex;
use App\Models\V1\Principal\Incomes;
use Illuminate\Support\Facades\Auth;
use App\Models\V1\Catalogo\Municipio;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationDetail;
use App\Models\V1\Principal\ReservationProduct;
use App\Models\V1\Principal\ReservationService;

class ReservationProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/reservation_product",
     *      operationId="getReservationProduct",
     *      tags={"Reservation"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todas las ventas de productos realizadas.",
     *      description="Retorna un array de ventas.",
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
        $data = ReservationProduct::with('product', 'reservation')->where('user_id', Auth::user()->id)->get();

        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/reservation_product",
     *      operationId="postReservationProduct",
     *      tags={"Reservation"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear una nueva venta de productos.",
     *      description="Retorna el objeto de la venta creada.",
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
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();
            $user = Auth::user()->id;

            if (!is_null($request->authorization_code)) {
                $detail = ReservationDetail::where('authorization_code', $request->authorization_code)->first();

                if (is_null($detail)) {
                    return $this->errorResponse("El código ingresado no es válido", 423);
                }

                $asign = ReservationService::where('reservations_detail_id', $detail->id)->first();

                if (!is_null($asign)) {
                    if (is_null($asign->start_date)) {
                        return $this->errorResponse("Es necesario que el servicio sea inicializado antes de crear una venta.", 423);
                    }
                }

                $reservation = Reservation::find($detail->reservation_id);
                $authorization_code = $detail->authorization_code;
            } else {
                $generar = Reservation::whereYear('created_at', date('Y'))->count();
                $generar += 1;
                $user = Auth::user()->id;

                $municipio = Municipio::find($request->municipality_id['id']);

                $cliente = Client::where('nit', $request->nit)->first();

                if (is_null($cliente)) {
                    $cliente = new Client();
                }

                $cliente->nit = $request->nit;
                $cliente->name = $request->name;
                $cliente->email = $request->email;
                $cliente->business = $request->business;
                $cliente->ubication = $request->ubication;
                $cliente->departament_id = $municipio->departament_id;
                $cliente->municipality_id = $municipio->id;
                $cliente->save();

                $data = $request->all();
                $data['code'] = $this->generadorCodigo($generar);
                $data['nit'] = $cliente->nit;
                $data['name'] = $cliente->name;
                $data['ubication'] = $municipio->getFullNameAttribute() . ', ' . $cliente->ubication;
                $data['total'] = 0;
                $data['total_reservation'] = 0;
                $data['total_product'] = 0;
                $data['reserva'] = false;
                $data['responsable'] = null;

                $data['client_id'] = $cliente->id;
                $data['user_id'] = $user;
                $data['status_id'] = Status::VENTA;
                $data['coin_id'] = 1;
                $data['way_to_pay_id'] =  $request->way_to_pay_id;

                $reservation = Reservation::create($data);
                $authorization_code = "venta sin reservar";
            }

            $mensajes = $this->kardex($request, $reservation, $user, $authorization_code);

            DB::commit();

            return $this->successResponse(['data' => "La venta fue realizada", "mensajes" => $mensajes]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    public function authorization_code($authorization_code)
    {
        try {
            $detail = ReservationDetail::where('authorization_code', $authorization_code)->first();

            if (is_null($detail)) {
                return $this->errorResponse("El código ingresado no es válido", 423);
            }

            $asign = ReservationService::where('reservations_detail_id', $detail->id)->first();

            if (!is_null($asign)) {
                if (is_null($asign->start_date)) {
                    return $this->errorResponse("Es necesario que el servicio sea inicializado antes de crear una venta.", 423);
                }
            }

            return $this->successResponse(['client' => $detail->client, 'mensaje' => 'El código es válido']);
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\V1\Principal\ReservationProduct  $reservationProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationProduct $reservationProduct)
    {
        //
    }

    private function kardex($request, $reservation, $user, $authorization_code)
    {
        $mensajes = array();
        foreach ($request->details as $value) {
            $informacion['producto'] = "El producto {$value['product_id']['name']} fue facturado en su totalidad";
            $informacion['codigo'] = "info";

            $kardex = Kardex::find($value['product_id']['id']);

            $comprados = Incomes::where('kardex_id', $kardex->id)->where('active', true)->get();

            if (count($comprados) == 0) {
                return $this->errorResponse("Es necesario hacer el ingreso del producto mediante el proceso de compra con el proveedor.", 423);
            }

            if ($kardex->stock > 0) {

                $consumido = 0;
                $temporal = $value['amount'];
                $cantidad = 0;

                $detail = new ReservationProduct();
                $detail->amount = 0;
                $detail->price = 0;
                $detail->discount = 0;
                $detail->sub_total = 0;
                $detail->authorization_code = $authorization_code;
                $detail->reservation_id = $reservation->id;
                $detail->kardex_id = $kardex->id;
                $detail->product_id = $kardex->product_id;
                $detail->coin_id = $kardex->coin_id;
                $detail->user_id = $user;
                $detail->client_id = $reservation->client_id;
                $detail->save();

                $sale = null;

                for ($i = 0; $i < $value['amount']; $i++) {
                    $comprados = Incomes::where('kardex_id', $kardex->id)->where('active', true)->first();

                    if (!is_null($comprados)) {
                        $comprados->consumed += 1;
                        $comprados->active = ($comprados->new_incomes - $comprados->consumed) == 0 ? false : true;
                        $comprados->save();

                        $consumido += 1;
                        $temporal -= 1;

                        if (!$comprados->active || $temporal == 0) {
                            $sale = Sale::create(
                                [
                                    'amount' => $consumido,
                                    'price' => $value['price'],
                                    'price_discount' => $value['price_discount'] > 0 ? $value['price'] - (($value['price'] * $value['price_discount']) / 100) : 0,
                                    'price_sub' => $value['price_discount'] > 0 ? ($consumido * $value['price']) - ($consumido * (($value['price'] * $value['price_discount']) / 100)) : ($consumido * $value['price']),
                                    'cost' => $comprados->cost,
                                    'individual' => $value['price_discount'] > 0 ? ($value['price'] - (($value['price'] * $value['price_discount']) / 100)) - $comprados->cost : $value['price'] - $comprados->cost,
                                    'cost_sub' => $consumido * $comprados->cost,
                                    'reservation_product_id' => $detail->id,
                                    'kardex_id' => $detail->kardex_id,
                                    'product_id' => $detail->product_id,
                                    'coin_id' => $detail->coin_id,
                                    'user_id' => $detail->user_id,
                                    'client_id' => $detail->client_id,
                                    'income_id' => $comprados->id
                                ]
                            );
                        }

                        $cantidad += 1;
                    }
                }

                if (!is_null($sale)) {
                    $detail->amount = $cantidad;
                    $detail->price = $sale->price;
                    $detail->discount = $sale->price_discount;
                    $detail->sub_total = $sale->price_sub;
                    $detail->save();

                    $reservation->total_product += $detail->sub_total;
                    //$reservation->total += $reservation->total_product;
                    $reservation->save();

                    $kardex->stock -= $detail->amount;

                    if ($kardex->stock > 0) {
                        $kardex->kardex_status_id = $kardex->stock > $kardex->notify ? KardexStatus::ALTA : KardexStatus::ALERTA;
                    } else {
                        $kardex->kardex_status_id = KardexStatus::BAJA;
                    }

                    $kardex->date_egress = Carbon::now();
                    $kardex->save();
                }

                if ($cantidad != $value['amount']) {
                    $informacion['producto'] = "El producto {$value['product_id']['name']} solo fueron facturados {$cantidad} items.";
                    $informacion['codigo'] = "warning";
                }

                array_push($mensajes, $informacion);
            } else {
                $informacion['producto'] = "El producto {$value['product_id']['name']} no tiene suficiente stock";
                $informacion['codigo'] = "error";

                array_push($mensajes, $informacion);
            }
        }

        return $mensajes;
    }
}
