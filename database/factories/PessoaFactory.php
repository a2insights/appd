<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pessoa;

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
