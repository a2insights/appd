<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tipo::create([
            'titulo' => 'Novo Associado',
            'descricao' => 'Novo Associado',
        ]);

        Tipo::create([
            'titulo' => 'Atualização Cadastral',
            'descricao' => 'Atualização Cadastral',
        ]);

        Tipo::create([
            'titulo' => 'Nova Carteirinha',
            'descricao' => 'Nova Carteirinha',
        ]);

        Tipo::create([
            'titulo' => 'Renovação de Carteirinha',
            'descricao' => 'Renovação de Carteirinha',
        ]);

        Tipo::create([
            'titulo' => 'Segunda Via de Carteirinha',
            'descricao' => 'Segunda Via de Carteirinha',
        ]);

        Tipo::create([
            'titulo' => 'Informações',
            'descricao' => 'Soliciatação de Informações',
        ]);
    }
}
