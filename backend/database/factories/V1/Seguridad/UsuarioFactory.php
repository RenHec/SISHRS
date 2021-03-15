<?php

namespace Database\Factories\V1\Seguridad;

use App\Models\V1\Catalogo\Departamento;
use App\Models\V1\Catalogo\Municipio;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cui' => '2342008040608',
            'first_name' => $this->faker->randomElement([$this->faker->firstNameMale, $this->faker->firstNameFemale]),
            'second_name' => $this->faker->randomElement([$this->faker->firstNameMale, $this->faker->firstNameFemale, null]),
            'surname' => $this->faker->lastName,
            'second_surname' => $guardo = $this->faker->randomElement([$this->faker->lastName, null]),
            'married_name' => is_null($guardo) ? null : $this->faker->randomElement([$this->faker->lastName, null]),
            'admin' => $this->faker->randomElement([Usuario::USUARIO_ADMINISTRADOR, Usuario::USUARIO_REGULAR]),
            'email' => $this->faker->unique()->freeEmail,
            'password' => 'admin',
            'observation' => $this->faker->randomElement([$this->faker->text($this->faker->numberBetween(100, 500)), null]),
            'ubication' => $this->faker->randomElement([$this->faker->address, null]),
            'phone' => $this->faker->unique()->numerify('########'),
            'departament_id' => Departamento::all()->random()->id,
            'municipality_id' => Municipio::all()->random()->id
        ];
    }
}
