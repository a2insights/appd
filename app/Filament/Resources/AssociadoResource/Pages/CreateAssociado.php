<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Resources\AssociadoResource;
use App\Models\Associado;
use Filament\Resources\Pages\CreateRecord;

class CreateAssociado extends CreateRecord
{
    protected static string $resource = AssociadoResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate()
    {
        Associado::noVersioning();
    }
}
