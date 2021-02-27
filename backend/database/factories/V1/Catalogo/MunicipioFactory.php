<?php

namespace Database\Factories\V1\Catalogo;

use App\Models\V1\Catalogo\Departamento;
use App\Models\V1\Catalogo\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

class MunicipioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Municipio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'departament_id' => Departamento::all()->random()->id
        ];
    }
}
