<?php

namespace Database\Seeders;

use App\Models\Associado;
use Illuminate\Database\Seeder;

class AssociadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Associado::factory()->count(200)->create();
    }
}
