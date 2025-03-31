<?php

namespace Database\Factories;

use App\Models\Encaminhamento;
use App\Models\Talento;
use App\Models\Vaga;
use Illuminate\Database\Eloquent\Factories\Factory;

class EncaminhamentoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Encaminhamento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'vaga_id' => Vaga::factory(),
            'talento_id' => Talento::factory(),
            'status' => $this->faker->randomElement(['nova', 'em_andamento', 'selecionado', 'desclassificado', 'finalizado']),
        ];
    }
}
