<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Principal\Room;
use App\Models\V1\Catalogo\Status;
use App\Models\V1\Principal\Client;
use Illuminate\Support\Facades\Crypt;
use App\Models\V1\Catalogo\TypeService;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Principal\Reservation;
use App\Models\V1\Principal\ReservationOfert;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'arrival_date',
        'departure_date',
        'accommodation',
        'quote',
        'authorization_code',
        'price',
        'sub',
        'ofert',
        'guest',
        'description',
        'reservation_id',
        'room_id',
        'coin_id',
        'client_id',
        'type_service_id',
        'status_id',
        'asign'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'authorization_code'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'arrival_date' => 'datetime:d/m/Y h:i:s a',
        'departure_date' => 'datetime:d/m/Y h:i:s a',
        'ofert' => 'boolean',
        'asign' => 'boolean'
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
    protected $appends = ['sub_formato'];

    /**
     * Get the clients full name.
     *
     * @return string
     */
    public function getSubFormatoAttribute()
    {
        $moneda = Coin::find($this->coin_id)->symbol;
        return "{$moneda} " . number_format($this->sub, 2, '.', ',');
    }

    /**
     * Get the room associated with the reservations_details.
     *
     * @return object
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * Get the coin associated with the reservations_details.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the reservation associated with the reservations_details.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    /**
     * Get the ofert associated with the reservations_details.
     *
     * @return object
     */
    public function ofert()
    {
        return $this->hasOne(ReservationOfert::class, 'reservation_detail_id', 'id');
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
     * Get the type_service associated with the reservations.
     *
     * @return object
     */
    public function type_service()
    {
        return $this->belongsTo(TypeService::class, 'type_service_id', 'id');
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
}
