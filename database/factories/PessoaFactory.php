<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PessoaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pessoa::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(),
            'cpf' => $this->faker->word(),
            'telefone_whatsapp' => $this->faker->word(),
            'telefone_celular' => $this->faker->word(),
        ];
    }
}
