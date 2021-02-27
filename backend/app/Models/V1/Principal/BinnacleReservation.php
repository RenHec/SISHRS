<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Movement;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'reservation_id',
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
        'start' => 'date:d/m/Y',
        'end' => 'date:d/m/Y',
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
     * Get the reservation associated with the binnacle_reservations.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
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
}
