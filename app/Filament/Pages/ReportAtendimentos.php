<?php

namespace App\Filament\Pages;

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

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-c-chart-bar';
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
