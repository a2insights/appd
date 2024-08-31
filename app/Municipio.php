<?php

namespace App;

use Spatie\LaravelData\Data;

class Municipio extends Data
{
    public function __construct(
        public string $nome,
        public int $codigoIbge,
        public string $uf,
    ) {}
}
