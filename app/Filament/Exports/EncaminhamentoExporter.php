<?php

namespace App\Filament\Exports;

use App\Models\Encaminhamento;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EncaminhamentoExporter extends Exporter
{
    protected static ?string $model = Encaminhamento::class;

    protected static string $type = 'encaminhamentos';

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('talento.associado.nome')
                ->label('Candidato'),

            ExportColumn::make('talento.associado.rg')
                ->label('RG'),

            ExportColumn::make('talento.associado.tipo_deficiencia')
                ->formatStateUsing(fn ($state) => $state?->getLabel())
                ->label('Deficiência'),

            ExportColumn::make('vaga.titulo')
                ->label('Vaga'),

            ExportColumn::make('vaga.cargos.nome')
                ->formatStateUsing(fn ($state, $record) => $record->vaga->cargos->map(fn ($cargo) => $cargo->nome)->implode(', '))
                ->label('Cargos(s)'),

            ExportColumn::make('vaga.empresa.nome')
                ->label('Empresa'),

            ExportColumn::make('created_at')
                ->label('Data do Encaminhamento')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i')),

            ExportColumn::make('status')
                ->formatStateUsing(fn ($state) => $state?->getLabel())
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Seu exportação de encaminhamentos foi concluída e '.number_format($export->successful_rows).' '.str('registro')->plural($export->successful_rows).' exportados.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('registro')->plural($failedRowsCount).' falharam.';
        }

        return $body;
    }
}
