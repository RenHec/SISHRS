<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Principal\Client;
use App\Models\V1\Principal\Kardex;
use App\Models\V1\Principal\Incomes;
use App\Models\V1\Principal\Product;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\V1\Principal\ReservationProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'price',
        'price_discount',
        'price_sub',
        'cost',
        'individual',
        'cost_sub',
        'reservation_product_id',
        'kardex_id',
        'product_id',
        'coin_id',
        'user_id',
        'client_id',
        'income_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'deleted_at' => 'datetime:d/m/Y h:i:s a'
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
    protected $appends = ['ganancia'];

    /**
     * Get the monto format.
     *
     * @return string
     */
    public function getGananciaAttribute()
    {
        return number_format(($this->price_sub - $this->cost_sub), 2, '.', ',');
    }

    /**
     * Get the reservation associated with the sales.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(ReservationProduct::class, 'reservation_product_id', 'id');
    }

    /**
     * Get the kardex associated with the sales.
     *
     * @return object
     */
    public function kardex()
    {
        return $this->belongsTo(Kardex::class, 'id', 'kardex_id');
    }

    /**
     * Get the product associated with the sales.
     *
     * @return object
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the coin associated with the sales.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'id', 'coin_id');
    }

    /**
     * Get the user associated with the sales.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    /**
     * Get the client associated with the sales.
     *
     * @return object
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Get the income associated with the sales.
     *
     * @return object
     */
    public function income()
    {
        return $this->belongsTo(Incomes::class, 'id', 'income_id');
    }
}
