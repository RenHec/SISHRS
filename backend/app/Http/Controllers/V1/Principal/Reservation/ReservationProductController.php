<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\V1\Principal\Sale;
use App\Models\V1\Catalogo\Status;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Client;
use App\Models\V1\Principal\Kardex;
use App\Http\Controllers\Controller;
use App\Models\V1\Principal\Incomes;
use Illuminate\Support\Facades\Auth;
use App\Models\V1\Catalogo\Municipio;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationProduct;

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
        $data = Reservation::with('client.phones', 'user')->where('reserva', false)->get();
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
        $this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $generar = Reservation::whereYear('created_at', date('Y'))->count();
            $generar += 1;
            $user = Auth::user()->id;

            $municipio = Municipio::find($request->municipality_id['id']);

            $cliente = Client::firstOrCreate(
                ['nit' => $request->nit],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'business' => $request->business,
                    'ubication' => $request->ubication,
                    'departament_id' => $municipio->departament_id,
                    'municipality_id' => $municipio->id
                ]
            );

            $data = $request->all();
            $data['code'] = $this->generadorCodigo($generar);
            $data['nit'] = $cliente->nit;
            $data['name'] = $cliente->name;
            $data['ubication'] = $municipio->getFullNameAttribute() . ', ' . $cliente->ubication;
            $data['total'] = 0;
            $data['total_reservation'] = 0;
            $data['total_product'] = 0;
            $data['reserva'] = false;
            $data['event'] = false;
            $data['responsable'] = null;

            $data['client_id'] = $cliente->id;
            $data['user_id'] = $user;
            $data['status_id'] = Status::VENTA;
            $data['coin_id'] = $request->coin_id;

            $reservation = Reservation::create($data);

            foreach ($request->details as $value) {

                $kardex = Kardex::where('product_id', $value['product_id']['id'])->withTrashed()->first();

                if ($kardex->stock >= $value['amount']) {
                    $detail = new ReservationProduct();
                    $detail->amount = $value['amount'];
                    $detail->price = $kardex->price;
                    $detail->discount = $kardex->discount;
                    $detail->sub_total = $kardex->discount > 0 ? ($detail->amount * $kardex->price) - ($detail->amount * (($detail->price * $kardex->discount) / 100)) : ($detail->amount * $kardex->price);
                    $detail->authorization_code = 'compra_directa';
                    $detail->kardex_id = $kardex->id;
                    $detail->product_id = $kardex->product_id;
                    $detail->coin_id = $kardex->coin_id;
                    $detail->user_id = $user;
                    $detail->client_id = $cliente->id;
                    $detail->save();

                    $consumido = 0;
                    $temporal = $detail->amount;
                    for ($i = 0; $i < $detail->amount; $i++) {
                        $comprados = Incomes::where('kardex_id', $kardex->id)->where('active', true)->first();

                        if (!is_null($comprados)) {
                            $comprados->consumed += 1;
                            $comprados->active = ($comprados->new_incomes - $comprados->consumed) == 0 ? false : true;
                            $comprados->save();

                            $consumido += 1;
                            $temporal -= 1;

                            if (!$comprados->active || $temporal == 0) {
                                Sale::create(
                                    [
                                        'amount' => $consumido,
                                        'price' => $detail->price,
                                        'price_discount' => $detail->discount,
                                        'price_sub' => $detail->discount > 0 ? ($consumido * $detail->price) - ($consumido * (($detail->price * $detail->discount) / 100)) : ($consumido * $detail->price),
                                        'cost' => $comprados->cost,
                                        'individual' => $detail->discount > 0 ? ($detail->price - (($detail->price * $detail->discount) / 100)) - $comprados->cost : $detail->price - $comprados->cost,
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
                        }
                    }

                    $kardex->stock -= $detail->amount;
                    $kardex->kardex_status_id = $kardex->stock > 0 ? $kardex->stock > $kardex->notify ? KardexStatus::ALTA : KardexStatus::ALERTA : KardexStatus::BAJA;
                    $kardex->date_egress = Carbon::now();
                    $kardex->save();

                    $reservation->total_product += $detail->sub;
                }
            }

            if ($reservation->total_product > 0) {
                $reservation->total = $reservation->total_product;
                $reservation->save();
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\V1\Principal\ReservationProduct  $reservationProduct
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationProduct $reservationProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\V1\Principal\ReservationProduct  $reservationProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservationProduct $reservationProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\V1\Principal\ReservationProduct  $reservationProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationProduct $reservationProduct)
    {
        //
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
}
