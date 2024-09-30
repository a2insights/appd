<?php

namespace App\Filament\Resources\TalentoResource\Pages;

use App\Filament\Resources\TalentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalento extends EditRecord
{
    protected static string $resource = TalentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
