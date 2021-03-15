<?php

namespace App\Http\Controllers\V1\Principal\Room;

use Illuminate\Http\Request;
use App\Models\V1\Principal\Room;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\RoomMassage;

class RoomMassageController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/massage/{massage}",
     *      operationId="updateMassage",
     *      tags={"Massage"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar los precios del servicio seleccionado.",
     *      description="Retorna el objeto de los precios actualizados.",
     *      @OA\Parameter(
     *          description="ID del servicio para actualizar",
     *          in="path",
     *          name="massage",
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
    public function update(Request $request, Room $massage)
    {
        //$this->validate($request, $this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            RoomMassage::where('room_id', $massage->id)->delete();

            foreach ($request->massages as $value) {
                RoomMassage::create(
                    [
                        'type_massage_id' => $value['id'],
                        'room_id' => $massage->id
                    ]
                );
            }

            DB::commit();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }
}
