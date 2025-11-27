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
        return $this->handleRequest(fn ($repository) => $repository->all(), false, 'municipio_service_all');
    }

    public function allByUf(string $uf): Collection
    {
        return $this->handleRequest(fn ($repository) => $repository->allByUf($uf), false, "municipio_service_allByUf_{$uf}");
    }

    public function find(string $codigoIbge): ?Municipio
    {
        return $this->handleRequest(fn ($repository) => $repository->find($codigoIbge), true, "municipio_service_find_{$codigoIbge}");
    }

    private function handleRequest(callable $callback, bool $expectSingle = false, string $cacheKey = 'default_key')
    {
        if (cache()->has($cacheKey)) {
            return cache($cacheKey);
        }

        foreach ($this->repositories as $name => $repositoryClass) {
            $repository = $this->getRepository($name);

            try {
                $result = $callback($repository);
                if ($expectSingle && ! is_null($result)) {
                    return cache()->remember($cacheKey, now()->addDays(30), fn () => $result);
                }

                if (! $expectSingle && $result->isNotEmpty()) {
                    return cache()->remember($cacheKey, now()->addDays(10), fn () => $result);
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
