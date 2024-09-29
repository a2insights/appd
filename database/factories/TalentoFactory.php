<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Associado;
use App\Models\Talento;

class TalentoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Talento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'associado_id' => Associado::factory(),
        ];
    }
}
