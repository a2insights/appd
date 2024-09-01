<?php

namespace App\Filament\Widgets;

use App\Models\Associado;
use App\Models\Carteirinha;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppdOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {

        $asssociadosQuery = Associado::query();
        $carteirinhasQuery = Carteirinha::query();

        $carteirinhasRenovadas = $asssociadosQuery->whereHas('carteirinhas', function ($query) {
            $query->where('status', 'ativa');
        }, '>', 1);

        return [
            Stat::make('Total de associados', $asssociadosQuery->count()),
            Stat::make('Associados Ativos', $asssociadosQuery->whereStatus('ativo')->count()),
            // Stat::make('Associados Não Ativos', $asssociadosQuery->whereIn('status', ['inativo', 'falecido'])->count()),
            Stat::make('Carteirinhas ativas', $carteirinhasQuery->whereStatus('ativa')->count()),
            // Stat::make('Carteirinhas vencidas', $carteirinhasQuery->whereStatus('vencida')->count()),
            Stat::make('Carteirinhas renovadas', $carteirinhasRenovadas->count()),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
