<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Exports\AtendimentoExporter;
use App\Filament\Resources\AtendimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAtendimentos extends ListRecords
{
    protected static string $resource = AtendimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(AtendimentoExporter::class),
            Actions\CreateAction::make()
                ->label('Novo Atendimento'),
        ];
    }
}
