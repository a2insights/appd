<?php

namespace App;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class Municipio extends Data
{
    public function __construct(
        public string $nome,
        public int $codigoIbge,
        public string $uf,
    ) {
        $this->uf = Str::lower($uf);
    }
}
