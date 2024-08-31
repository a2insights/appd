<?php

namespace App\Repositories\Municipio;

use Illuminate\Support\Collection;

interface MunicipioRepository
{
    /*
    * @return Collection<\App\Municipio>
    */
    public function all(): Collection;

    /*
    * @return Collection<\App\Municipio>
    * @param string $uf
    */
    public function allByUf($uf): Collection;

    /*
    * @return \App\Municipio
    * @param string $codigoIbge
    */
    public function find(string $codigoIbge): \App\Municipio;
}
