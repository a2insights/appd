<?php

namespace App\Filament\Widgets;

use App\Models\Associado;
use App\Models\Carteirinha;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppdOverview extends BaseWidget
{
    use \Filament\Widgets\Concerns\InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = null;
        $endDate = null;

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if (count($dates) === 2) {
                $startDate = \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $dates[0]);
                $endDate = \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $dates[1]);
            }
        }

        $applyFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query;
        };

        return [
            Stat::make('Total de associados', $applyFilter(Associado::query())->count()),
            Stat::make('Associados Ativos', $applyFilter(Associado::query())->whereStatus('ativo')->count()),
            Stat::make('Associados Não Ativos', $applyFilter(Associado::query())->whereIn('status', ['inativo', 'falecido'])->count()),
            Stat::make('Carteirinhas ativas', $applyFilter(Carteirinha::query())->whereStatus('ativa')->count()),
            Stat::make('Carteirinhas vencidas', $applyFilter(Carteirinha::query())->whereStatus('vencida')->count()),
            Stat::make('Carteirinhas renovadas', $applyFilter(Carteirinha::query())
                ->select('associado_id')
                ->groupBy('associado_id')
                ->havingRaw('count(*) > 1')
                ->get()
                ->count()),
            Stat::make('Associados com carteirinha ativa', $applyFilter(Associado::query())->whereHas('carteirinhas', function ($query) {
                $query->whereStatus('ativa');
            })->count()),
            Stat::make('Associados sem carteirinha ativa', $applyFilter(Associado::query())->whereDoesntHave('carteirinhas', function ($query) {
                $query->whereStatus('ativa');
            })->count()),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
