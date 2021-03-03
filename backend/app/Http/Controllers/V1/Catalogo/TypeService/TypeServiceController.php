<?php

namespace App\Http\Controllers\V1\Catalogo\TypeService;

use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\TypeService;

class TypeServiceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/typeservice",
     *      operationId="getTypeServices",
     *      tags={"Type Service"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de servicio registrados en la base de datos.",
     *      description="Retorna un array de tipos de servicio.",
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
        $data = TypeService::all();
        return $this->showAll($data);
    }
}
