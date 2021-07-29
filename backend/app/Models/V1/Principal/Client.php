<?php

namespace App\Models\V1\Principal;

use App\Models\V1\Catalogo\Municipio;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Catalogo\Departamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nit',
        'name',
        'business',
        'email',
        'ubication',
        'departament_id',
        'municipality_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'business' => 'boolean'
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
    protected $appends = ['full_name'];

    /**
     * Get the clients full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the department associated with the user.
     *
     * @return object
     */
    public function departament()
    {
        return $this->belongsTo(Departamento::class, 'departament_id', 'id');
    }

    /**
     * Get the municipality associated with the user.
     *
     * @return object
     */
    public function municipality()
    {
        return $this->belongsTo(Municipio::class, 'municipality_id', 'id');
    }

    /**
     * Get the reservations associated with the client.
     *
     * @return array
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id', 'id');
    }

    /**
     * Get the phones associated with the client.
     *
     * @return array
     */
    public function phones()
    {
        return $this->hasMany(ClientPhone::class, 'client_id', 'id');
    }
}
