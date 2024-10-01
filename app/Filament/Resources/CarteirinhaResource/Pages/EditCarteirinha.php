<?php

namespace App\Filament\Resources\CarteirinhaResource\Pages;

use App\Filament\Resources\CarteirinhaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarteirinha extends EditRecord
{
    protected static string $resource = CarteirinhaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
