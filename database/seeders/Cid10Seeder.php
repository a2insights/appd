<?php

namespace Database\Seeders;

use App\Models\Cid10;
use Illuminate\Database\Seeder;

class Cid10Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = array_map('str_getcsv', file(base_path('database/seeders/cid10.csv')));

        unset($csv[0]);

        $data = collect($csv)->map(function ($row) {
            return [
                'codigo' => $row[0],
                'descricao' => $row[1],
            ];
        })->toArray();

        Cid10::insert($data);
    }
}
