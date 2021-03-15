<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\TypeCharge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomPrice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price',
        'default',
        'type_charge_id',
        'room_id',
        'web',
        'coin_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'default' => 'boolean',
        'web' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['monto'];

    /**
     * Get the clients full name.
     *
     * @return string
     */
    public function getMontoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->price, 2, '.', ',');
    }
    
    /**
     * Get the type_charge associated with the rooms.
     *
     * @return object
     */
    public function type_charge()
    {
        return $this->belongsTo(TypeCharge::class, 'type_charge_id', 'id');
    }
}
