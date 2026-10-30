<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalidadesSeeder extends Seeder
{
    private const ESTADOS = [
        12 => ['ac', 'Acre'], 27 => ['al', 'Alagoas'], 16 => ['ap', 'Amapá'], 13 => ['am', 'Amazonas'],
        29 => ['ba', 'Bahia'], 23 => ['ce', 'Ceará'], 53 => ['df', 'Distrito Federal'], 32 => ['es', 'Espírito Santo'],
        52 => ['go', 'Goiás'], 21 => ['ma', 'Maranhão'], 51 => ['mt', 'Mato Grosso'], 50 => ['ms', 'Mato Grosso do Sul'],
        31 => ['mg', 'Minas Gerais'], 15 => ['pa', 'Pará'], 25 => ['pb', 'Paraíba'], 41 => ['pr', 'Paraná'],
        26 => ['pe', 'Pernambuco'], 22 => ['pi', 'Piauí'], 33 => ['rj', 'Rio de Janeiro'], 24 => ['rn', 'Rio Grande do Norte'],
        43 => ['rs', 'Rio Grande do Sul'], 11 => ['ro', 'Rondônia'], 14 => ['rr', 'Roraima'], 42 => ['sc', 'Santa Catarina'],
        35 => ['sp', 'São Paulo'], 28 => ['se', 'Sergipe'], 17 => ['to', 'Tocantins'],
    ];

    public function run(): void
    {
        foreach (self::ESTADOS as $codigo => [$uf, $nome]) {
            DB::table('estados')->updateOrInsert(['codigo_ibge' => $codigo], ['uf' => $uf, 'nome' => $nome]);
        }

        $file = database_path('seeders/municipios.csv');
        $handle = fopen($file, 'r');
        fgetcsv($handle);
        $batch = [];

        while (($row = fgetcsv($handle)) !== false) {
            $batch[] = ['codigo_ibge' => (int) $row[0], 'nome' => $row[1], 'estado_codigo_ibge' => (int) $row[5]];
            if (count($batch) === 500) {
                DB::table('municipios')->upsert($batch, ['codigo_ibge'], ['nome', 'estado_codigo_ibge']);
                $batch = [];
            }
        }

        fclose($handle);
        if ($batch) {
            DB::table('municipios')->upsert($batch, ['codigo_ibge'], ['nome', 'estado_codigo_ibge']);
        }
    }
}
