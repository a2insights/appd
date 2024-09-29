<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Aco;
use App\Models\Atendimento;
use App\Models\Historico;

class HistoricoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Historico::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'acao_id' => Aco::factory(),
            'atendimento_id' => Atendimento::factory(),
            'data' => '{}',
        ];
    }
}
