<?php

namespace App\Models\V1\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    public const PENDIENTE = "PENDIENTE";
    public const ACEPTO = "ACEPTO";
    public const NO_ACEPTO = "NO ACEPTO";

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firm',
        'answer',
        'reservation_id',
        'url'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a'
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
    protected $appends = ['picture'];

    /**
     * Get the pictures documents link base64 photo.
     *
     * @return string
     */
    public function getPictureAttribute()
    {
        $imagen = Storage::disk('firm')->exists($this->firm); //Preguntamos si la imagen existe creada local

        if (!$imagen) { //Si la imagen no existe
            return null;
        }

        $imagen = Storage::disk('firm')->get($this->firm); //Seleccionar la imagen
        return "data:application/jpg;base64," . base64_encode($imagen);
    }

    /**
     * Get the reservation associated with the contract.
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
