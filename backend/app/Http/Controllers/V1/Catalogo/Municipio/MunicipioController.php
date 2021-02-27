<?php

namespace App\Http\Controllers\V1\Catalogo\Municipio;

use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\Municipio;

class MunicipioController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/municipio",
     *      operationId="getMunicipalities",
     *      tags={"Municipio"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los municipios registrados en la base de datos.",
     *      description="Retorna un array de municipios.",
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
        $data = Municipio::get();
        return $this->showAll($data);
    }
}
