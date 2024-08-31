<?php

namespace App\Services;

use App\Municipio;
use App\Repositories\Municipio\MunicipioRepositoryBrasilApi;
use App\Repositories\Municipio\MunicipioRepositoryIbge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class MunicipioService
{
    private $repositories = [
        'ibge' => MunicipioRepositoryIbge::class,
        'brasil_api' => MunicipioRepositoryBrasilApi::class,
    ];

    public function all(): Collection
    {
        return $this->handleRequest(fn ($repository) => $repository->all());
    }

    public function allByUf(string $uf): Collection
    {
        return $this->handleRequest(fn ($repository) => $repository->allByUf($uf));
    }

    public function find(string $codigoIbge): ?Municipio
    {
        return $this->handleRequest(fn ($repository) => $repository->find($codigoIbge), true);
    }

    private function handleRequest(callable $callback, bool $expectSingle = false)
    {
        foreach ($this->repositories as $name => $repositoryClass) {
            $repository = $this->getRepository($name);

            try {
                $result = $callback($repository);
                if ($expectSingle && ! is_null($result)) {
                    return $result;
                }

                if (! $expectSingle && $result->isNotEmpty()) {
                    return $result;
                }
            } catch (Throwable $e) {
                Log::warning("Error fetching data from {$name} repository: ".$e->getMessage());
            }
        }

        return $expectSingle ? null : collect();
    }

    private function getRepository(string $name)
    {
        $repository = $this->repositories[$name];

        return app($repository);
    }
}
