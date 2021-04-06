<?php

namespace App\Http\Controllers\V1\Principal\Kardex;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Incomes;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Catalogo\Supplier;
use App\Models\V1\Principal\Kardex;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomesController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/catalogo/income",
     *      operationId="getIncome",
     *      tags={"Kardex"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos ingresos de producto al inventario.",
     *      description="Retorna un array de ingresos.",
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
        $data = Incomes::with('supplier', 'product', 'user')->withTrashed()->get();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/income",
     *      operationId="postIncome",
     *      tags={"Kardex"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear nuevos ingresos de producto en el sistema.",
     *      description="Retorna el objeto del ingreso creado.",
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

            $supplier = Supplier::firstOrCreate(
                ['name' => $request->supplier_id]
            );
            $user = Auth::user()->id;

            foreach ($request->incomes as $value) {
                $kardex = Kardex::where('product_id', $value['product_id']['id'])->withTrashed()->first();

                $income = Incomes::create(
                    [
                        'codigo' => $value['codigo'],
                        'cost' => $value['cost'],
                        'new_incomes' => $value['new_incomes'],
                        'stock_current' => $kardex->stock,
                        'stock_new' => $value['new_incomes'] + $kardex->stock,
                        'consumed' => 0,
                        'expiration' => null,
                        'active' => true,
                        'supplier_id' => $supplier->id,
                        'product_id' => $kardex->product_id,
                        'kardex_id' => $kardex->id,
                        'coin_id' => $kardex->coin_id,
                        'user_id' => $user
                    ]
                );

                $kardex->stock += $income->new_incomes;
                $kardex->kardex_status_id = $kardex->stock > $kardex->notify ? KardexStatus::ALTA : KardexStatus::ALERTA;
                $kardex->date_entry = Carbon::now();
                $kardex->save();
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }
}
