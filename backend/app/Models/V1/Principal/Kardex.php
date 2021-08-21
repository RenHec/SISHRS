<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Principal\Sale;
use App\Models\V1\Principal\Incomes;
use App\Models\V1\Principal\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\V1\Catalogo\KardexStatus;
use App\Models\V1\Principal\ReservationProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kardex extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kardex';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock',
        'notify',
        'price',
        'discount',
        'date_entry',
        'date_egress',
        'product_id',
        'coin_id',
        'kardex_status_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'deleted_at' => 'datetime:d/m/Y h:i:s a',
        'date_entry' => 'datetime:d/m/Y h:i:s a',
        'date_egress' => 'datetime:d/m/Y h:i:s a'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'date_entry', 'date_egress'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['monto'];

    /**
     * Get the monto format.
     *
     * @return string
     */
    public function getMontoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->price, 2, '.', ',');
    }

    /**
     * Get the product associated with the kardex.
     *
     * @return object
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the status associated with the kardex.
     *
     * @return object
     */
    public function status()
    {
        return $this->belongsTo(KardexStatus::class, 'kardex_status_id', 'id');
    }

    /**
     * Get the coin associated with the kardex.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the incomes associated with the kardex.
     *
     * @return array
     */
    public function incomes()
    {
        return $this->hasMany(Incomes::class, 'kardex_id', 'id');
    }

    /**
     * Get the reservation associated with the kardex.
     *
     * @return array
     */
    public function reservation()
    {
        return $this->hasMany(ReservationProduct::class, 'kardex_id', 'id');
    }

    /**
     * Get the sale associated with the kardex.
     *
     * @return array
     */
    public function sale()
    {
        return $this->hasMany(Sale::class, 'kardex_id', 'id');
    }
}
