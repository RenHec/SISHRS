<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\Supplier;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incomes extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'incomes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'cost',
        'new_incomes',
        'stock_current',
        'stock_new',
        'consumed',
        'expiration',
        'active',
        'supplier_id',
        'product_id',
        'kardex_id',
        'coin_id',
        'user_id'
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
        'expiration' => 'date:d/m/Y',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'expiration'];

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
        return "{$moneda} " . number_format($this->cost, 2, '.', ',');
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
}
