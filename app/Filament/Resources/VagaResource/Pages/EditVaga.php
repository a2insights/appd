<?php

namespace App\Filament\Resources\VagaResource\Pages;

use App\Filament\Resources\VagaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVaga extends EditRecord
{
    protected static string $resource = VagaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
