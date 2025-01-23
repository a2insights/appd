<?php

namespace Database\Factories;

use App\Models\Cid10;
use Illuminate\Database\Eloquent\Factories\Factory;

class Cid10Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cid10::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->word(),
            'descricao' => $this->faker->text(),
        ];
    }
}
