<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Exports\AssociadoExporter;
use App\Filament\Resources\AssociadoResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAssociados extends ListRecords
{
    use ExposesTableToWidgets;

    // width full
    protected function getTableWidth(): string
    {
        return 'full';
    }

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

    protected function getHeaderWidgets(): array
    {
        return AssociadoResource::getWidgets();
    }
}
