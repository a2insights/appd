<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Exports\AssociadoExporter;
use App\Filament\Resources\AssociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssociados extends ListRecords
{
    protected static string $resource = AssociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\ExportAction::make()
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(AssociadoExporter::class),
            Actions\CreateAction::make()
                ->label('Novo')
                ->color('primary')
                ->icon('heroicon-o-plus'),
        ];
    }
}
