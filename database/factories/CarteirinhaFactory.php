<?php

namespace Database\Factories;

use App\Models\Carteirinha;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarteirinhaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Carteirinha::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['ativa', 'cancelada', 'vencida']),
            'data_emissao' => $this->faker->date(),
            'data_vencimento' => $this->faker->date(),
        ];
    }
}
