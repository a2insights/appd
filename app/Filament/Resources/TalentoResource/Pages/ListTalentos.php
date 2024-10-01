<?php

namespace App\Filament\Resources\TalentoResource\Pages;

use App\Filament\Resources\TalentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalentos extends ListRecords
{
    protected static string $resource = TalentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Adicionar Talento'),
        ];
    }
}
