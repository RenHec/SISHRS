<?php

namespace App\Http\Controllers\V1\Catalogo\Coin;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\Coin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class CoinController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/coin",
     *      operationId="getCoins",
     *      tags={"Coin"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los tipos de moneda registradas en la base de datos.",
     *      description="Retorna un array de tipos de moneda.",
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
        $data = Coin::all();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/catalogo/coin",
     *      operationId="postCoin",
     *      tags={"Coin"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo tipo de moneda en el sistema.",
     *      description="Retorna el objeto del tipo de moneda creado.",
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
            Coin::create($request->all());
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/catalogo/coin/{coin}",
     *      operationId="updateCoin",
     *      tags={"Coin"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el tipo de moneda seleccionado.",
     *      description="Retorna el objeto del tipo de moneda actualizado.",
     *      @OA\Parameter(
     *          description="ID del tipo de moneda para actualizar",
     *          in="path",
     *          name="coin",
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
    public function update(Request $request, Coin $coin)
    {
        $this->validate($request, $this->rules($coin->id), $this->messages());

        try {
            $coin->symbol = $request->symbol;
            $coin->name = $request->name;

            if(!$coin->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $coin->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/catalogo/coin/{coin}",
     *      operationId="deleteCoin",
     *      tags={"Coin"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar el tipo de coin seleccionado.",
     *      description="Retorna el objeto del tipo de coin eliminado.",
     *      @OA\Parameter(
     *          description="ID del tipo de coin para eliminar",
     *          in="path",
     *          name="coin",
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
    public function destroy(Coin $coin)
    {
        try {
            $coin->forceDelete();
            return $this->successResponse('Registro desactivado');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'symbol' => 'required|max:5',
            'name' => is_null($id) ? 'required|max:50|unique:coins,name' : "required|max:50|unique:coins,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'symbol.required' => 'El el símbolo de la moneda es obligatorio.',
            'symbol.max'  => 'El el símbolo de la moneda debe tener menos de :max carácteres.',

            'name.required' => 'El nombre del tipo de cama es obligatorio.',
            'name.max'  => 'El nombre del tipo de cama debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del tipo de cama ingresado ya existe en el sistema.',
        ];
    }
}
