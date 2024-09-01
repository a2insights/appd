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
        'brasil_api' => MunicipioRepositoryBrasilApi::class,
        'ibge' => MunicipioRepositoryIbge::class,
    ];

    public function all(): Collection
    {
        return $this->handleRequest(fn ($repository) => $repository->all(), false, 'all');
    }

    public function allByUf(string $uf): Collection
    {
        return $this->handleRequest(fn ($repository) => $repository->allByUf($uf), false, 'allByUf');
    }

    public function find(string $codigoIbge): ?Municipio
    {
        return $this->handleRequest(fn ($repository) => $repository->find($codigoIbge), true, 'find');
    }

    private function handleRequest(callable $callback, bool $expectSingle = false, $method = 'all')
    {
        $cacheKey = "municipio_service_{$method}";
        if (cache()->has($cacheKey)) {
            return cache($cacheKey);
        }

        foreach ($this->repositories as $name => $repositoryClass) {
            $repository = $this->getRepository($name);

            try {
                $result = $callback($repository);
                if ($expectSingle && ! is_null($result)) {
                    return cache()->remember($cacheKey, now()->addDay(), fn () => $result);
                }

                if (! $expectSingle && $result->isNotEmpty()) {
                    return cache()->remember($cacheKey, now()->addDay(), fn () => $result);
                }
            } catch (Throwable $e) {
                Log::warning("Error fetching data from {$name} repository: ".$e->getMessage());
            }
        }

        return $expectSingle ? null : collect([]);
    }

    private function getRepository(string $name)
    {
        $repository = $this->repositories[$name];

        return app($repository);
    }
}
