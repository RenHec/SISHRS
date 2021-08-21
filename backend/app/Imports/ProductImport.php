<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\V1\Principal\Kardex;
use App\Models\V1\Principal\Incomes;
use App\Models\V1\Principal\Product;
use App\Models\V1\Catalogo\SubCategory;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Principal\ChangePrice;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            if (!is_null($value[0])) {
                $data['name'] = $value[0];
                $data['price'] = $value[1];
                $data['discount'] = $value[2];
                $data['description'] = $value[3];
                $data['category_id'] = SubCategory::find($value[4])->category_id;
                $data['sub_category_id'] = $value[4];
                $data['coin_id'] = $value[5];

                $product = Product::create($data);

                ChangePrice::create(
                    [
                        'price' => $product->price,
                        'discount' => $product->discount,
                        'product_id' => $product->id,
                        'coin_id' => $product->coin_id
                    ]
                );

                $kardex = Kardex::create(
                    [
                        'stock' => 0,
                        'notify' => $value[6],
                        'price' => $product->price,
                        'discount' => $product->discount,
                        'date_entry' => null,
                        'date_egress' => null,
                        'product_id' => $product->id,
                        'coin_id' => $product->coin_id,
                        'kardex_status_id' => KardexStatus::ALTA
                    ]
                );

                $income = Incomes::create(
                    [
                        'codigo' => str_pad(strval($kardex->id), 5, "0", STR_PAD_LEFT),
                        'cost' => 5,
                        'new_incomes' => 200,
                        'stock_current' => $kardex->stock,
                        'stock_new' => 200 + $kardex->stock,
                        'consumed' => 0,
                        'expiration' => null,
                        'active' => true,
                        'supplier_id' => 1,
                        'product_id' => $kardex->product_id,
                        'kardex_id' => $kardex->id,
                        'coin_id' => $kardex->coin_id,
                        'user_id' => 1
                    ]
                );

                $kardex->stock += $income->new_incomes;
                $kardex->kardex_status_id = $kardex->stock > $kardex->notify ? KardexStatus::ALTA : KardexStatus::ALERTA;
                $kardex->date_entry = Carbon::now();
                $kardex->save();
            }
        }
    }
}
