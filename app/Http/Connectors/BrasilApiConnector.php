<?php

namespace App\Http\Connectors;

use Saloon\Http\Connector;

class BrasilApiConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://brasilapi.com.br/api';
    }
}
