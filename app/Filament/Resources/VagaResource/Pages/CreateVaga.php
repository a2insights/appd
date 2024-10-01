<?php

namespace App\Filament\Resources\VagaResource\Pages;

use App\Filament\Resources\VagaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVaga extends CreateRecord
{
    protected static string $resource = VagaResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
