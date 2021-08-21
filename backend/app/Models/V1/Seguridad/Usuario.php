<?php

namespace App\Models\V1\Seguridad;

use Laravel\Passport\HasApiTokens;
use App\Models\V1\Catalogo\Departamento;
use App\Models\V1\Catalogo\Municipio;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    const USUARIO_ADMINISTRADOR = 'ADMINISTRADOR';
    const USUARIO_REGULAR = 'REGULAR';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cui',
        'first_name',
        'second_name',
        'surname',
        'second_surname',
        'married_name',
        'admin',
        'photo',
        'email',
        'observation',
        'ubication',
        'phone',
        'departament_id',
        'municipality_id',
        'password',
        'photo',
        'occupied'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
        'photo'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:d/m/Y h:i:s a',
        'created_at' => 'datetime:d/m/Y h:i:s a',
        'updated_at' => 'datetime:d/m/Y h:i:s a',
        'deleted_at' => 'datetime:d/m/Y h:i:s a',
        'occupied' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name', 'picture'];

    /**
     * Search user passport.
     *
     * @return object
     */
    public function findForPassport($username)
    {
        return $this->where('cui', $username)->first();
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return str_replace('  ', ' ', "{$this->first_name} {$this->second_name} {$this->surname} {$this->second_surname} {$this->married_name}");
    }

    /**
     * Get the user's link base64 photo.
     *
     * @return string
     */
    public function getPictureAttribute()
    {
        $imagen = Storage::disk('user')->exists($this->photo); //Preguntamos si la imagen existe creada local

        if (!$imagen) { //Si la imagen no existe
            return null;
        }

        $imagen = Storage::disk('user')->get($this->photo); //Seleccionar la imagen
        return "data:application/jpg;base64," . base64_encode($imagen);
    }

    /**
     * Set the user's email.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Set the user's password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
     * Get the rols associated with the user.
     *
     * @return array
     */
    public function rols()
    {
        return $this->hasMany(UsuarioRol::class, 'user_id', 'id');
    }
}
