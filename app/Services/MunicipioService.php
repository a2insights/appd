<?php

namespace App\Services;

use App\Models\Municipio as MunicipioModel;
use App\Municipio;
use Illuminate\Support\Collection;

class MunicipioService
{
    public function all(): Collection
    {
        return MunicipioModel::query()->with('estado')->orderBy('nome')->get()
            ->map(fn (MunicipioModel $municipio) => new Municipio($municipio->nome, (int) $municipio->codigo_ibge, $municipio->estado->uf));
    }

    public function allByUf(string $uf): Collection
    {
        return MunicipioModel::query()->whereHas('estado', fn ($query) => $query->where('uf', strtolower($uf)))
            ->orderBy('nome')->get()
            ->map(fn (MunicipioModel $municipio) => new Municipio($municipio->nome, (int) $municipio->codigo_ibge, strtolower($uf)));
    }

    public function find(string $codigoIbge): ?Municipio
    {
        $municipio = MunicipioModel::query()->with('estado')->find($codigoIbge);

        return $municipio ? new Municipio($municipio->nome, (int) $municipio->codigo_ibge, $municipio->estado->uf) : null;
    }
}
