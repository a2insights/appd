<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Vaga;

class VagaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vaga::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->numberBetween(-100000, 100000),
            'descricao' => $this->faker->numberBetween(-100000, 100000),
            'requisitos' => '{}',
            'inicia_em' => $this->faker->dateTime(),
            'finaliza_em' => $this->faker->dateTime(),
        ];
    }
}
