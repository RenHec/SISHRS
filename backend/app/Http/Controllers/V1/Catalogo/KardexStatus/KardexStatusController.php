<?php

namespace App\Http\Controllers\V1\Catalogo\KardexStatus;

use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\KardexStatus;

class KardexStatusController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/kardex_status",
     *      operationId="getKardexStatus",
     *      tags={"Kardex"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los estados en que puede estar el inventario.",
     *      description="Retorna un array de estados.",
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
        $data = KardexStatus::withCount('kardex')->get();
        return $this->showAll($data);
    }
}
