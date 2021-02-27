<?php

namespace App\Models\V1\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PictureRoom extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pictures_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo',
        'position',
        'view',
        'room_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'view' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'photo'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['picture'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the pictures rooms link base64 photo.
     *
     * @return string
     */
    public function getPictureAttribute()
    {
        $imagen = Storage::disk('room')->exists($this->photo); //Preguntamos si la imagen existe creada local

        if (!$imagen) { //Si la imagen no existe
            return null;
        }

        $imagen = Storage::disk('room')->get($this->photo); //Seleccionar la imagen
        return "data:application/jpg;base64," . base64_encode($imagen);
    }
}
