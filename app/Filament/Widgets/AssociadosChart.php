<?php

namespace App\Filament\Widgets;

use App\Models\Associado;
use App\Models\Carteirinha;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;

class AssociadosChart extends ChartWidget
{
    protected static ?string $heading = 'Cadastros no último ano';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $associadoData = Trend::query(Associado::query())
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        $carteirinhaData = Trend::query(Carteirinha::query())
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Associados',
                    'data' => $associadoData->map(fn ($value) => $value->aggregate)->toArray(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
                [
                    'label' => 'Carteirinhas',
                    'data' => $carteirinhaData->map(fn ($value) => $value->aggregate)->toArray(),
                    'backgroundColor' => '#FFCE56',
                    'borderColor' => '#FFDB9A',
                ],
            ],
            'labels' => $associadoData->map(fn ($value) => Carbon::parse($value->date)->format('M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Comparação de cadastros de associados e carteirinhas no último ano.';
    }
}
