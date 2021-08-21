<?php

namespace App\Http\Controllers\V1\Principal\Product;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Principal\Product;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Catalogo\SubCategory;
use App\Models\V1\Principal\ChangePrice;
use App\Models\V1\Principal\Kardex;
use App\Models\V1\Principal\PictureProduct;
use Illuminate\Support\Facades\Storage;

class ProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/product",
     *      operationId="getProducts",
     *      tags={"Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los productos registrados en la base de datos.",
     *      description="Retorna un array de productos.",
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
        $data = Product::with('category', 'sub_category', 'coin', 'prices', 'kardex')->withTrashed()->orderByDesc('id')->get();
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/room",
     *      operationId="postRoom",
     *      tags={"Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear una nueva habitación en el sistema.",
     *      description="Retorna el objeto de la habitación creada.",
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

            $data = $request->all();
            $data['category_id'] = SubCategory::find($request->sub_category_id['id'])->category_id;
            $data['sub_category_id'] = $request->sub_category_id['id'];
            $data['coin_id'] = $request->coin_id['id'];

            $product = Product::create($data);

            foreach ($request->pictures as $key => $value) {
                if (isset($value['photo']) && !is_null($value['photo']) && !empty($value['photo'])) {
                    $picture_name = Str::random(10);

                    $img = $this->getB64Image($value['photo']);
                    $image = Image::make($img);
                    $image->encode('jpg', 70);
                    $path = "{$product->id}/{$picture_name}.jpg";
                    Storage::disk('product')->put($path, $image);

                    PictureProduct::create(
                        [
                            'photo' => $path,
                            'position' => $key + 1,
                            'view' => true,
                            'product_id' => $product->id
                        ]
                    );
                }
            }

            ChangePrice::create(
                [
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'product_id' => $product->id,
                    'coin_id' => $product->coin_id
                ]
            );

            Kardex::create(
                [
                    'stock' => 0,
                    'notify' => $request->notify,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'date_entry' => null,
                    'date_egress' => null,
                    'product_id' => $product->id,
                    'coin_id' => $product->coin_id,
                    'kardex_status_id' => KardexStatus::BAJA
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
     * @OA\Put(
     *      path="/service/rest/v1/principal/product/{product}",
     *      operationId="updateProduct",
     *      tags={"Product"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el producto seleccionado.",
     *      description="Retorna el objeto del producto actualizada.",
     *      @OA\Parameter(
     *          description="ID del producto para actualizar",
     *          in="path",
     *          name="product",
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
    public function update(Request $request, Product $product)
    {
        //$this->validate($request, $this->rules($product->id), $this->messages());

        try {

            DB::beginTransaction();

            ChangePrice::where('product_id', $product->id)->update(
                [
                    'coin_id' => $request->coin_id['id']
                ]
            );

            Kardex::where('product_id', $product->id)->update(
                [
                    'coin_id' => $request->coin_id['id'],
                    'notify' => $request->notify
                ]
            );

            $product->name = $request->name;
            $product->description = $request->description;
            $product->category_id = $request->category_id['id'];
            $product->sub_category_id = $request->sub_category_id['id'];

            if (!$product->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $product->save();

            DB::commit();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/picture/{picture}",
     *      operationId="deletePicture",
     *      tags={"Picture"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Cambiar estado del producto seleccionado.",
     *      description="Retorna el objeto del producto con nuevo estado.",
     *      @OA\Parameter(
     *          description="ID del producto para cambiar de estado.",
     *          in="path",
     *          name="picture",
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
    public function destroy($product)
    {
        $product = Product::withTrashed()->find($product);
        if (is_null($product->deleted_at)) {
            $product->delete();
            $message = 'descativado';
        } else {
            $product->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => is_null($id) ? 'required|max:50|unique:products,name' : "required|max:50|unique:products,name,{$id}",
            'price' => is_null($id) ? 'required|between:0.25,999999' : '',
            'discount' => is_null($id) ? 'nullable|between:0,100' : '',
            'description' => 'required',
            'sub_category_id.id' => 'required|integer|exists:sub_categories,id',
            'coin_id.id' => 'required|integer|exists:coins,id',
            'notify' => 'required|between:1,999'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max'  => 'El nombre del producto debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre del producto ya se encuentra registrado en la base de datos.',

            'price.required' => 'El precio del producto es obligatorio.',
            'price.between' => 'El precio del producto tiene que estar en un rango entre :min y :max.',

            'discount.between' => 'El descueto tiene que estar en un rango entre :min y :max.',

            'description.required' => 'La descripción del producto es obligatoria.',

            'price.required' => 'El precio de la habitación es obligatorio.',
            'price.between' => 'El precio de la habitación tiene que estar en un rango entre :min y :max.',


            'sub_category_id.id.required' => 'La categoría y sub categoría es obligatorio.',
            'sub_category_id.id.integer' => 'La categoría y sub categoría no es un número.',
            'sub_category_id.id.exists' => 'La categoría y sub categoría no existe en la base de datos.',

            'coin_id.id.required' => 'El tipo de moneda es obligatorio.',
            'coin_id.id.integer' => 'El tipo de moneda no es un número.',
            'coin_id.id.exists' => 'El tipo de moneda no existe en la base de datos.',

            'notify.required' => 'La cantidad para la alerta del stock es obligatorio.',
            'notify.between' => 'La cantidad para la alerta del stock tiene que estar en un rango entre :min y :max.'
        ];
    }
}
