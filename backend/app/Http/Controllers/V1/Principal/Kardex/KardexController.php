<?php

namespace App\Http\Controllers\V1\Principal\Kardex;

use App\Models\V1\Principal\Kardex;
use App\Http\Controllers\ApiController;

class KardexController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/kardex",
     *      operationId="getKardex",
     *      tags={"Kardex"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los productos registrados en el inventario.",
     *      description="Retorna un array de productos registrados en el inventario.",
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
        $data = Kardex::with('product', 'status')->withTrashed()->get();
        return $this->showAll($data);
    }
}
