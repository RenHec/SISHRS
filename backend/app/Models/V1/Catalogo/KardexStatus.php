<?php

namespace App\Models\V1\Catalogo;

use App\Models\V1\Principal\Kardex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KardexStatus extends Model
{
    use HasFactory;

    const ALTA = 1;
    const ALERTA = 2;
    const BAJA = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kardex_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color'
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
     * Get the kardex associated with the status.
     *
     * @return array
     */
    public function kardex()
    {
        return $this->hasMany(Kardex::class, 'kardex_status_id', 'id');
    }
}
