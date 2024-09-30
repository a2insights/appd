<?php

namespace App\Filament\Resources\TalentoResource\Pages;

use App\Filament\Resources\TalentoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTalento extends CreateRecord
{
    protected static string $resource = TalentoResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
