<?php

namespace App\Filament\Widgets;

use App\Models\Associado;
use App\Models\Atendimento;
use App\Models\Carteirinha;
use App\Models\Encaminhamento;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;

class AssociadosChart extends ChartWidget
{
    protected static ?string $heading = 'Cadastros e Atividades no Último Ano';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

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

        $atendimentoData = Trend::query(Atendimento::query())
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        $encaminhamentoData = Trend::query(Encaminhamento::query())
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
                    'datalabels' => [
                        'align' => 'start',
                        'anchor' => 'start',
                    ],
                ],
                [
                    'label' => 'Carteirinhas',
                    'data' => $carteirinhaData->map(fn ($value) => $value->aggregate)->toArray(),
                    'backgroundColor' => '#FFCE56',
                    'borderColor' => '#FFDB9A',
                    'datalabels' => [
                        'align' => 'center',
                        'anchor' => 'center',
                    ],
                ],
                [
                    'label' => 'Atendimentos',
                    'data' => $atendimentoData->map(fn ($value) => $value->aggregate)->toArray(),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FFB1C1',
                    'datalabels' => [
                        'align' => 'end',
                        'anchor' => 'end',
                    ],
                ],
                [
                    'label' => 'Encaminhamentos',
                    'data' => $encaminhamentoData->map(fn ($value) => $value->aggregate)->toArray(),
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#A4DEDF',
                    'datalabels' => [
                        'align' => 'top',
                        'anchor' => 'top',
                    ],
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
        return 'Comparativo de novos associados, carteirinhas emitidas, atendimentos e encaminhamentos realizados no último ano.';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'datalabels' => [
                    'display' => true,
                    'color' => '#fff',
                    'borderRadius' => 4,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.7)',
                    'font' => [
                        'weight' => 'bold',
                    ],
                    'formatter' => RawJs::make(<<<JS
                        function(value) {
                            alert(1)
                            return value > 0 ? value : '';
                        }
                    JS),
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
