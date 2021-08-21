<?php

namespace App\Http\Controllers\V1\Principal\Kardex;

use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\Sale;

class SaleController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/sale",
     *      operationId="getSale",
     *      tags={"Kardex"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los egresos de producto del inventario.",
     *      description="Retorna un array de egresos.",
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
        $data = Sale::with('reservation.reservation', 'product', 'user', 'client')->withTrashed()->get();
        return $this->showAll($data);
    }
}
