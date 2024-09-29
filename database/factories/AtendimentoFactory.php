<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Associado;
use App\Models\Atendimento;
use App\Models\Pessoa;
use App\Models\Tipo;

class AtendimentoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Atendimento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tipo_id' => Tipo::factory(),
            'em_andamento' => $this->faker->boolean(),
            'finalizado_automaticamente' => $this->faker->boolean(),
            'pessoa_id' => Pessoa::factory(),
            'associado_id' => Associado::factory(),
            'finalizado_em' => $this->faker->dateTime(),
        ];
    }
}
