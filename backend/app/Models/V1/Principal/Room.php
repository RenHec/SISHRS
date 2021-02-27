<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Coin;
use App\Models\V1\Catalogo\TypeBed;
use App\Models\V1\Catalogo\TypeRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'name',
        'amount_people',
        'amount_bed',
        'price',
        'description',
        'type_bed_id',
        'type_room_id',
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
        'deleted_at' => 'datetime:d/m/Y h:i:s a'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the type_bed associated with the rooms.
     *
     * @return object
     */
    public function type_bed()
    {
        return $this->belongsTo(TypeBed::class, 'type_bed_id', 'id');
    }

    /**
     * Get the type_room associated with the rooms.
     *
     * @return object
     */
    public function type_room()
    {
        return $this->belongsTo(TypeRoom::class, 'type_room_id', 'id');
    }

    /**
     * Get the coin associated with the rooms.
     *
     * @return object
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    /**
     * Get the pictures associated with the rooms.
     *
     * @return array
     */
    public function pictures()
    {
        return $this->hasMany(PictureRoom::class, 'room_id', 'id');
    }

    /**
     * Get the oferts associated with the rooms.
     *
     * @return array
     */
    public function oferts()
    {
        return $this->hasMany(OfertRoom::class, 'room_id', 'id');
    }
}
