<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationProduct extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'price',
        'discount',
        'sub_total',
        'authorization_code',
        'reservation_id',
        'kardex_id',
        'product_id',
        'coin_id',
        'user_id',
        'client_id',
        'pagado'
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
        'pagado' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['monto_precio', 'monto_sub', 'monto_descuento'];

    /**
     * Get the monto format.
     *
     * @return string
     */
    public function getMontoPrecioAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->price, 2, '.', ',');
    }

    /**
     * Get the monto format.
     *
     * @return string
     */
    public function getMontoSubAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->sub_total, 2, '.', ',');
    }

    /**
     * Get the monto format.
     *
     * @return string
     */
    public function getMontoDescuentoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->discount, 2, '.', ',');
    }

    /**
     * Get the supplier associated with the incomes.
     *
     * @return object
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * Get the product associated with the incomes.
     *
     * @return object
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the kardex associated with the incomes.
     *
     * @return object
     */
    public function kardex()
    {
        return $this->belongsTo(Kardex::class, 'kardex_id', 'id');
    }

    /**
     * Get the coin associated with the incomes.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the user associated with the incomes.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    /**
     * Get the reservation associated with the incomes.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
