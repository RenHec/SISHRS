<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Seguridad\Usuario;
use App\Models\V1\Catalogo\TypeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BinnacleReservation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'binnacle_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start',
        'end',
        'active',
        'days',
        'subtraction',
        'movement_id',
        'reservation_detail_id',
        'user_id',
        'type_service_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the movement associated with the binnacle_reservations.
     *
     * @return object
     */
    public function movement()
    {
        return $this->belongsTo(Movement::class, 'movement_id', 'id');
    }

    /**
     * Get the reservation_detail associated with the binnacle_reservations.
     *
     * @return object
     */
    public function reservation_detail()
    {
        return $this->belongsTo(ReservationDetail::class, 'reservation_detail_id', 'id');
    }

    /**
     * Get the user associated with the binnacle_reservations.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    /**
     * Get the type_service associated with the binnacle_reservations.
     *
     * @return object
     */
    public function type_service()
    {
        return $this->belongsTo(TypeService::class, 'type_service_id', 'id');
    }
}
