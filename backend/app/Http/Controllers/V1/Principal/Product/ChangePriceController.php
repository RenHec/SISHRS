<?php

namespace App\Http\Controllers\V1\Principal\Product;

use Illuminate\Http\Request;
use App\Models\V1\Principal\Product;
use App\Http\Controllers\ApiController;
use App\Models\V1\Principal\ChangePrice;
use App\Models\V1\Principal\Kardex;
use Illuminate\Support\Facades\DB;

class ChangePriceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\V1\Principal\Product  $change_price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $change_price)
    {
        $this->validate($request, $this->rules(), $this->messages());
        try {

            DB::beginTransaction();

            $ultimo_precio = ChangePrice::where('product_id', $change_price->id)->first();

            if(!is_null($ultimo_precio)) {
                $ultimo_precio->delete();
            }

            $nuevo = new ChangePrice();
            $nuevo->price = $request->price;
            $nuevo->discount = $request->discount;
            $nuevo->product_id = $change_price->id;
            $nuevo->coin_id = $change_price->coin_id;
            $nuevo->save();

            $change_price->price = $request->price;
            $change_price->discount = $request->discount;
            $change_price->save();

            $kardex = Kardex::where('product_id', $change_price->id)->first();

            $kardex->price = $request->price;
            $kardex->discount = $request->discount;
            $kardex->save();

            DB::commit();

            return $this->successResponse('Registro agregado.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    //Reglas de validaciones
    public function rules()
    {
        $validar = [
            'price' => 'required|between:0.25,999999',
            'discount' => 'nullable|between:0,100'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'price.required' => 'El precio es obligatorio.',
            'price.between' => 'El precio tiene que estar en un rango entre :min y :max.',

            'discount.between' => 'El descuento tiene que estar en un rango entre :min y :max.'
        ];
    }
}
