<?php

namespace App\Http\Connectors\Ibge;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMunicipioRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/localidades/municipios';
    }
}
