<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\WayToPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancePrice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advance_price';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'link',
        'document',
        'authorization_link',
        'status',
        'payment_percentage',
        'contract_id',
        'reservation_id',
        'way_to_pay_id',
        'coin_id',
        'pay'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'pay' => 'boolean'
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
    protected $appends = ['monto', 'picture'];

    /**
     * Get the pictures documents link base64 photo.
     *
     * @return string
     */
    public function getPictureAttribute()
    {
        if (!is_null($this->document)) {
            $imagen = Storage::disk('advance_price_document')->exists($this->document); //Preguntamos si la imagen existe creada local

            if (!$imagen) { //Si la imagen no existe
                return null;
            }

            $imagen = Storage::disk('advance_price_document')->get($this->document); //Seleccionar la imagen
            return "data:application/jpg;base64," . base64_encode($imagen);
        }
    }

    /**
     * Get the clients full name.
     *
     * @return string
     */
    public function getMontoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->amount, 2, '.', ',');
    }

    /**
     * Get the contract associated with the advance.
     *
     * @return object
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    /**
     * Get the reservation associated with the advance.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    /**
     * Get the way_to_pay associated with the advance.
     *
     * @return object
     */
    public function way_to_pay()
    {
        return $this->belongsTo(WayToPay::class, 'way_to_pay_id', 'id');
    }
}
