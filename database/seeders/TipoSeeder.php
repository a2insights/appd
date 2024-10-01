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
            'descricao' => 'Cadastro de Novo Associado',
        ]);

        Tipo::create([
            'titulo' => 'Atualização Cadastral',
            'descricao' => 'Atualiza os dados cadastrais do associado',
        ]);

        Tipo::create([
            'titulo' => 'Nova Carteirinha',
            'descricao' => 'Emissão de Nova Carteirinha',
        ]);

        Tipo::create([
            'titulo' => 'Renovação de Carteirinha',
            'descricao' => 'Renovar carteirinha do associado',
        ]);

        Tipo::create([
            'titulo' => 'Segunda Via de Carteirinha',
            'descricao' => 'Emissão de Segunda Via de Carteirinha',
        ]);

        Tipo::create([
            'titulo' => 'Informações',
            'descricao' => 'Soliciatação de Informações',
        ]);
    }
}
