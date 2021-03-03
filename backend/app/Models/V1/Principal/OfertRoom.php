<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\TypeCharge;
use App\Models\V1\Principal\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfertRoom extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oferts_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accommodation',
        'price',
        'observation',
        'start_date',
        'end_date',
        'active',
        'room_id',
        'coin_id',
        'type_charge_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'start_date' => 'datetime:d/m/Y h:i:s a',
        'end_date' => 'datetime:d/m/Y h:i:s a',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the room associated with the oferts_rooms.
     *
     * @return object
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * Get the coin associated with the oferts_rooms.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the type_change associated with the oferts_rooms.
     *
     * @return object
     */
    public function type_change()
    {
        return $this->belongsTo(TypeCharge::class, 'type_charge_id', 'id');
    }
}
