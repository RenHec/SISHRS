<?php

namespace App\Http\Controllers\V1\Principal\Product;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\V1\Principal\Product;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\PictureProduct;

class PictureProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/picture_product",
     *      operationId="postPictureProduct",
     *      tags={"Picture Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización masiva de las posiciones de las fotografías del producto seleccionada.",
     *      description="Retorna un mensaje indicado el resultado del proceso.",
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
        $validar = [
            'pictures.*.id' => 'required',
            'pictures.*.position' => 'required'
        ];
        $message = [
            'pictures.*.id.required' => 'El ID de la fotografía es obligatorio',
            'pictures.*.position.required' => 'La posición de la fotográfica es obligatorio'
        ];
        $this->validate($request, $validar, $message);

        try {
            DB::beginTransaction();

            foreach ($request->pictures as $key => $value) {
                $image = PictureProduct::find($value['id']);
                $image->position = $value['position'];
                $image->save();
            }

            DB::commit();

            return $this->successResponse('Actualización de posiciones de las fotografías');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_product/{picture_product}",
     *      operationId="findPictureProductbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todas fotografías de la habitación seleccionada.",
     *      description="Retorna un array de las fotografías de la habitación seleccionada.",
     *      @OA\Parameter(
     *          description="ID de la habitación a consultar",
     *          in="path",
     *          name="picture_product",
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
    public function show(Product $picture_product)
    {
        $picture_product  = PictureProduct::where('product_id', $picture_product->id)->orderBy('position', 'ASC')->get();
        return $this->showAll($picture_product);
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_product/view/{picture_product}",
     *      operationId="findPictureProductViewbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de estado de la vista de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_product",
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
    public function view(PictureProduct $picture_product)
    {
        try {
            $picture_product->view = $picture_product->view == true ? false : true;
            $picture_product->save();

            return $this->successResponse('Actualización de estado de la fotografía');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_product/up/{picture_product}",
     *      operationId="findPictureProductUpbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de la posición de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_product",
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
    public function up(PictureProduct $picture_product)
    {
        try {
            DB::beginTransaction();

            $arriba = $picture_product->position + 1;

            $posicion_baja = PictureProduct::where('product_id', $picture_product->product_id)->where('position', $arriba)->first();
            if (!is_null($posicion_baja)) {
                $posicion_baja->position = $picture_product->position;
                $posicion_baja->save();
            }

            $picture_product->position = $arriba;
            $picture_product->save();

            DB::commit();

            return $this->successResponse('Imagen actualizada');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_product/down/{picture_product}",
     *      operationId="findPictureProductDownbyId",
     *      tags={"Picture Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de la posición de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_product",
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
    public function down(PictureProduct $picture_product)
    {

        try {
            DB::beginTransaction();

            $abajo = $picture_product->position - 1;

            $posicion_sube = PictureProduct::where('product_id', $picture_product->product_id)->where('position', $abajo)->first();
            if (!is_null($posicion_sube)) {
                $posicion_sube->position = $picture_product->position;
                $posicion_sube->save();
            }

            $picture_product->position = $abajo;
            $picture_product->save();

            DB::commit();

            return $this->successResponse('Imagen actualizada');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/picture_product/{picture_product}",
     *      operationId="updatePictureProduct",
     *      tags={"Picture Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Agregar una nueva fotografía al producto seleccionado.",
     *      description="Retorna el objeto del producto actualizado.",
     *      @OA\Parameter(
     *          description="ID del producto para agregar nuevas fotografías.",
     *          in="path",
     *          name="picture_product",
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
    public function update(Request $request, Product $picture_product)
    {
        try {

            foreach ($request->pictures as $key => $value) {
                if (isset($value['photo']) && !is_null($value['photo']) && !empty($value['photo'])) {
                    $picture_name = Str::random(10);

                    $img = $this->getB64Image($value['photo']);
                    $image = Image::make($img);
                    $image->encode('jpg', 70);
                    $path = "{$picture_product->id}/{$picture_name}.jpg";
                    Storage::disk('product')->put($path, $image);

                    PictureProduct::create(
                        [
                            'photo' => $path,
                            'position' => $key + 1,
                            'view' => true,
                            'product_id' => $picture_product->id
                        ]
                    );
                }
            }

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/picture_product/{picture_product}",
     *      operationId="deletePictureProduct",
     *      tags={"Picture Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía eliminada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía para eliminar",
     *          in="path",
     *          name="picture_product",
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
    public function destroy(PictureProduct $picture_product)
    {
        try {
            $picture_product->forceDelete();
            Storage::disk('product')->exists($picture_product->photo) ? Storage::disk('product')->delete($picture_product->photo) : null;
            return $this->successResponse('Registro eliminado.');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
