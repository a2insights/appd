<?php

namespace App\Filament\Resources\EncaminhamentoResource\Pages;

use App\Filament\Exports\EncaminhamentoExporter;
use App\Filament\Resources\EncaminhamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEncaminhamentos extends ListRecords
{
    protected static string $resource = EncaminhamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(EncaminhamentoExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
