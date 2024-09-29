<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Resources\AtendimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAtendimentos extends ListRecords
{
    protected static string $resource = AtendimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
