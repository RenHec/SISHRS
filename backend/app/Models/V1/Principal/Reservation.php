<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\Status;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'arrival_date',
        'departure_date',
        'accommodation',
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
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'arrival_date' => 'datetime:d/m/Y h:i:s a',
        'departure_date' => 'datetime:d/m/Y h:i:s a'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

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
}
