<?php

namespace App\Repositories\Municipio;

use App\Http\Connectors\Ibge\GetMunicipioRequest;
use App\Http\Connectors\IbgeConnector;
use App\Municipio;
use Illuminate\Support\Collection;

class MunicipioRepositoryIbge implements MunicipioRepository
{
    public function __construct(protected IbgeConnector $connector) {}

    public function all(): Collection
    {
        $request = new GetMunicipioRequest;

        $response = $this->connector->send($request);

        $data = $response->array();

        return collect($data)->map(function ($item) {
            return new Municipio($item['nome'], $item['id'], $item['microrregiao']['mesorregiao']['UF']['sigla']);
        });
    }

    public function allByUf($uf): Collection
    {
        $municipios = $this->all();

        return $municipios->filter(function ($municipio) use ($uf) {
            return $municipio->uf === $uf;
        });
    }

    public function find(string $codigoIbge): Municipio
    {
        $municipio = $this->all()->first(function ($municipio) use ($codigoIbge) {
            return $municipio->codigoIbge === $codigoIbge;
        });

        return $municipio;
    }
}
