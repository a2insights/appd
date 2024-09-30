<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use Illuminate\Contracts\Support\Htmlable;

class ReportAtendimentos extends ReportAssociados
{
    protected static string $routePath = '/report-atendimentos';

    public static function getNavigationLabel(): string
    {
        return 'Atendimentos';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Relatório de Atendimentos';
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AtendimentosReport::class,
        ];
    }
}
