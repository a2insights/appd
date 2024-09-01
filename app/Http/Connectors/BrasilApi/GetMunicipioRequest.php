<?php

namespace App\Http\Connectors\BrasilApi;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMunicipioRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $uf) {}

    public function resolveEndpoint(): string
    {
        return "/ibge/municipios/v1/{$this->uf}";
    }
}
