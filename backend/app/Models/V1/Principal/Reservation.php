<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\Status;
use App\Models\V1\Principal\Client;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\ReservationDetail;
use App\Models\V1\Principal\ReservationService;
use App\Models\V1\Principal\BinnacleReservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'nit',
        'name',
        'ubication',
        'total',
        'total_reservation',
        'total_product',
        'event',
        'responsable',
        'reserva',

        'no_mesa',

        'document',
        'payment',
        'total_restaurant',
        'way_to_pay',

        'client_id',
        'user_id',
        'status_id',
        'coin_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'event' => 'boolean',
        'reserva' => 'boolean',
        'payment' => 'boolean'
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
     * Get the clients full name.
     *
     * @return string
     */
    public function getMontoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->total, 2, '.', ',');
    }

    /**
     * Get the pictures documents link base64 photo.
     *
     * @return string
     */
    public function getPictureAttribute()
    {
        $imagen = Storage::disk('document')->exists($this->photo); //Preguntamos si la imagen existe creada local

        if (!$imagen) { //Si la imagen no existe
            return null;
        }

        $imagen = Storage::disk('document')->get($this->photo); //Seleccionar la imagen
        return "data:application/jpg;base64," . base64_encode($imagen);
    }

    /**
     * Get the client associated with the reservations.
     *
     * @return object
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Get the user associated with the reservations.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    /**
     * Get the status associated with the reservations.
     *
     * @return object
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    /**
     * Get the coin associated with the reservations.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the detail associated with the client.
     *
     * @return array
     */
    public function detail()
    {
        return $this->hasMany(ReservationDetail::class, 'reservation_id', 'id');
    }

    /**
     * Get the service associated with the client.
     *
     * @return array
     */
    public function service()
    {
        return $this->hasMany(ReservationService::class, 'reservation_id', 'id');
    }

    /**
     * Get the binnacle associated with the reservation.
     *
     * @return array
     */
    public function binnacle()
    {
        return $this->hasMany(BinnacleReservation::class, 'reservation_id', 'id');
    }

    /**
     * Get the products associated with the reservation.
     *
     * @return array
     */
    public function products()
    {
        return $this->hasMany(ReservationProduct::class, 'reservation_id', 'id');
    }
}
