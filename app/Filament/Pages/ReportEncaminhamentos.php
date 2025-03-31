<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\Support\Htmlable;

class ReportEncaminhamentos extends ReportAssociados
{
    protected static string $routePath = '/report-encaminhamentos';

    public static function getNavigationLabel(): string
    {
        return 'Encaminhamentos';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Relatório de Encaminhamentos';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-c-presentation-chart-bar';
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\EncaminhamentosReport::class,
        ];
    }
}
