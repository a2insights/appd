<?php

namespace App\Filament\Resources\CarteirinhaResource\Pages;

use App\CarteirinhaStatus;
use App\Filament\Resources\CarteirinhaResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListCarteirinhas extends ListRecords
{
    protected static string $resource = CarteirinhaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todas'),
            'ativas' => Tab::make()->query(fn ($query) => $query->whereStatus(CarteirinhaStatus::ATIVA)),
            'vencidas' => Tab::make()->query(fn ($query) => $query->whereStatus(CarteirinhaStatus::VENCIDA)),
            'canceladas' => Tab::make()->query(fn ($query) => $query->whereStatus(CarteirinhaStatus::CANCELADA)),
        ];
    }
}
