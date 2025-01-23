<?php

namespace Database\Factories;

use App\Models\Acao;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcaoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Acao::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->numberBetween(-100000, 100000),
            'descricao' => $this->faker->numberBetween(-100000, 100000),
            'icon' => $this->faker->numberBetween(-100000, 100000),
        ];
    }
}
