<?php

namespace App\Http\Controllers\V1\Principal\Room;

use Illuminate\Http\Request;
use App\Models\V1\Principal\Room;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\RoomPrice;
use App\Http\Controllers\ApiController;

class RoomPriceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/price/{price}",
     *      operationId="updatePrice",
     *      tags={"Price"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Agregar precio al servicio seleccionado.",
     *      description="Retorna el objeto de precio del servicio agregado.",
     *      @OA\Parameter(
     *          description="ID de precio del servicio agregado",
     *          in="path",
     *          name="price",
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
    public function update(Request $request, Room $price)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            RoomPrice::create(
                [
                    'price' => str_replace(',','', $request->price),
                    'default' => false,
                    'type_charge_id' => $request->type_charge_id['id'],
                    'room_id' => $price->id,
                    'web' => $request->web,
                    'coin_id' => $request->coin_id['id']
                ]
            );

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/price/{price}",
     *      operationId="deletePrice",
     *      tags={"Price"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar precio seleccionado.",
     *      description="Retorna el objeto del precio seleccionado.",
     *      @OA\Parameter(
     *          description="ID del precio seleccionado.",
     *          in="path",
     *          name="price",
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
    public function destroy(RoomPrice $price)
    {
        $price->delete();
        $message = 'eliminado';

        return $this->successResponse("Registro {$message}");
    }
}
