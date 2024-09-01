<?php

namespace App\Http\Connectors;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

class IbgeConnector extends Connector
{
    use HasTimeout;

    protected int $connectTimeout = 8;

    protected int $requestTimeout = 4;

    public function resolveBaseUrl(): string
    {
        return 'https://servicodados.ibge.gov.br/api/v1';
    }
}
