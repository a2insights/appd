<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use App\Filament\Resources\PessoaResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePessoa extends CreateRecord
{
    protected static string $resource = PessoaResource::class;

    protected static bool $canCreateAnother = false;
}
