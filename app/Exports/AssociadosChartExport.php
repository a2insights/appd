<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class AssociadosChartExport implements FromArray
{
    public function __construct(protected array $data) {}

    public function array(): array
    {
        return $this->data;
    }
}
