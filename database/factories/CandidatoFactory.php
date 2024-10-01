<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Candidato;
use App\Models\Talento;
use App\Models\Vaga;

class CandidatoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidato::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'vaga_id' => Vaga::factory(),
            'talento_id' => Talento::factory(),
            'status' => $this->faker->randomElement(["nova","em_andamento","selecionado","desclassificado","finalizado"]),
        ];
    }
}
