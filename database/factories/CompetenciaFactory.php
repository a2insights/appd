<?php

namespace Database\Factories;

use App\Models\Competencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetenciaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Competencia::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->numberBetween(-100000, 100000),
            'descricao' => $this->faker->numberBetween(-100000, 100000),
        ];
    }
}
