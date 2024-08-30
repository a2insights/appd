<?php

namespace App\Filament\Resources\AssociadoResource\Widgets;

use App\Filament\Resources\AssociadoResource\Pages\ListAssociados;
use App\Models\Associado;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AssociadosOverview extends BaseWidget
{
    use ExposesTableToWidgets;
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 4;

    protected function getTablePage(): string
    {
        return ListAssociados::class;
    }

    protected function getStats(): array
    {
        // $associadoData = Trend::query(Associado::query(fn ($query) => $query->$this->getPageTableQuery()))
        //     ->between(
        //         start: now()->subYear(),
        //         end: now(),
        //     )
        //     ->perMonth()
        //     ->count();

        // $currentMonth = $associadoData->last()->aggregate;
        // $lastMonth = $associadoData->reverse()->skip(1)->first()->aggregate;
        // $growth = $currentMonth - $lastMonth;

        // $icon = $growth > 0 ? 'heroicon-m-arrow-up' : 'heroicon-m-arrow-down';
        // $color = $growth > 0 ? 'success' : 'danger';

        return [
            Stat::make('Total', $this->getPageTableQuery()->count()),
            // ->description('Crescimento no último mês: '.$growth)
            // ->color($color)
            // ->descriptionIcon($icon)
            // ->chart(
            //     $associadoData
            //         ->map(fn (TrendValue $value) => $value->aggregate)
            //         ->toArray()
            // ),
            Stat::make('Ativos', $this->getPageTableQuery()->whereStatus('ativo')->count()),
            Stat::make('Não Ativos', $this->getPageTableQuery()->whereIn('status', ['inativo', 'falecido'])->count()),
        ];
    }
}
