<?php

namespace Database\Factories;

use App\Models\Vaga;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'titulo' => $this->faker->jobTitle(),
            'descricao' => $this->faker->text(),
            'requisitos' => '{}',
            'inicia_em' => $this->faker->dateTime(),
            'finaliza_em' => $this->faker->dateTime(),
        ];
    }
}
