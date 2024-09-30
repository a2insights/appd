<?php

namespace App\Filament\Resources\VagaResource\Pages;

use App\Filament\Resources\VagaResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListVagas extends ListRecords
{
    protected static string $resource = VagaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todas'),
            'ativas' => Tab::make()->query(fn ($query) => $query->whereAtiva(true)),
            'inativas' => Tab::make()->query(fn ($query) => $query->whereAtiva(false)),
        ];
    }
}
