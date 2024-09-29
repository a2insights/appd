<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Tipo;

class TipoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tipo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->word(),
            'descricao' => $this->faker->word(),
        ];
    }
}
