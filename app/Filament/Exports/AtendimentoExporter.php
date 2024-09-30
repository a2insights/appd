<?php

namespace App\Filament\Exports;

use App\Models\Atendimento;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;

class AtendimentoExporter extends Exporter
{
    use ExportConcerns;

    protected static ?string $model = Atendimento::class;

    protected static string $type = 'atendimentos';

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('associado.id')
                ->label('Associado ID'),
            ExportColumn::make('associado.nome')
                ->label('Associado Nome'),
            ExportColumn::make('associado.status')->state(fn ($record) => $record->associado->status->getLabel())
                ->label('Associado Status'),
            ExportColumn::make('tipos.titulo'),
            ExportColumn::make('created_at')
                ->label('Data do Atendimento')
                ->formatStateUsing(fn ($record) => $record->created_at->format('d/m/Y H:i:s')),
        ];
    }
}
