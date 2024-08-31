<?php

namespace App\Repositories\Municipio;

use App\Http\Connectors\BrasilApi\GetMunicipioRequest;
use App\Http\Connectors\BrasilApiConnector;
use App\Municipio;
use Illuminate\Support\Collection;

class MunicipioRepositoryBrasilApi implements MunicipioRepository
{
    public function __construct(protected BrasilApiConnector $connector) {}

    public function all(): Collection
    {
        $municipios = [];

        foreach ($this->ufs() as $uf) {
            array_push($municipios, ...$this->allByUf($uf)->all());
        }

        return collect($municipios);
    }

    public function allByUf($uf): Collection
    {
        $request = new GetMunicipioRequest($uf);

        $cacheKey = "brasil_api_municipios_$uf}";
        $responseData = cache()->remember($cacheKey, now()->addDay(), function () use ($request) {
            return $this->connector->send($request)->array();
        });

        return collect($responseData)->map(function ($item) use ($uf) {
            return new Municipio($item['nome'], $item['codigo_ibge'], $uf);
        });
    }

    public function find(string $codigoIbge): Municipio
    {
        $municipio = $this->all()->first(function ($municipio) use ($codigoIbge) {
            return $municipio->codigoIbge === $codigoIbge;
        });

        return $municipio;
    }

    private function ufs(): Collection
    {
        return collect(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO']);
    }
}
