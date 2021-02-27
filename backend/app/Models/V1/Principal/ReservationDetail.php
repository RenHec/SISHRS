<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'price',
        'ofert',
        'reservation_id',
        'room_id',
        'coin_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

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
     * Get the ofert associated with the reservations_details.
     *
     * @return object
     */
    public function ofert()
    {
        return $this->hasOne(ReservationOfert::class, 'reservation_detail_id', 'id');
    }
}
