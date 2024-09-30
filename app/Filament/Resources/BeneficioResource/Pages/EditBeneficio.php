<?php

namespace App\Filament\Resources\BeneficioResource\Pages;

use App\Filament\Resources\BeneficioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeneficio extends EditRecord
{
    protected static string $resource = BeneficioResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
