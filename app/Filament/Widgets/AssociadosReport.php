<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssociadosReport extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Associados';

    protected static ?string $maxHeight = '450px';

    protected static ?int $total = 0;

    protected $reportType = null;

    protected function getType(): string
    {
        return 'bar';
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getOptions(): array
    {
        $xAxis = $this->getFilter('xAxis');
        $group = $this->getFilter('group');

        if (! $xAxis || ! $group) {
            return [];
        }

        return $this->getOptionsData($xAxis, $group);
    }

    public function getOptionsData($xAxis, $group): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'align' => 'center',
                ],
                'datalabels' => [
                    'display' => true,
                    'font' => [
                        'size' => 24,
                        'weight' => 'bold',
                    ],
                    'textShadowColor' => 'rgba(0, 0, 0, 0.5)',
                ],
            ],
            'options' => [
                'barThickness' => 20,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'stacked' => false,
                        'title' => [
                            'display' => true,
                            'text' => 'Quantidade',
                        ],
                    ],
                    'x' => [
                        'stacked' => false,
                        'title' => [
                            'display' => true,
                            'text' => $xAxis,
                        ],
                        // Adicionado para ocultar as labels dos valores do eixo X
                        'ticks' => [
                            'display' => false,
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $xAxis = $this->getFilter('xAxis');
        $group = $this->getFilter('group');

        $filters = $this->filters;
        unset($filters['xAxis'], $filters['group']);

        if (! $xAxis || ! $group) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return $this->generateDatasetsAndLabels($xAxis, $group, $filters, $this->reportType);
    }

    private function getFilter(string $key, $default = null)
    {
        return $this->filters[$key] ?? $default;
    }

    public function generateDatasetsAndLabels(string $xAxis, string $group, ?array $filters = null, ?string $type = null): array
    {
        $chartData = ['labels' => [], 'datasets' => []];

        $labels = DB::table('associados')
            ->select("associados.$xAxis")  // Explicit table reference
            ->groupBy("associados.$xAxis")  // Explicit table reference
            ->pluck($xAxis)
            ->toArray();

        $colors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(255, 0, 0, 0.7)',  // Red
            'rgba(0, 255, 0, 0.7)',  // Green
            'rgba(0, 0, 255, 0.7)',  // Blue
            'rgba(255, 255, 0, 0.7)', // Yellow
            'rgba(0, 255, 255, 0.7)', // Cyan
            'rgba(255, 0, 255, 0.7)', // Magenta
            'rgba(128, 0, 0, 0.7)',   // Maroon
            'rgba(128, 128, 0, 0.7)', // Olive
            'rgba(0, 128, 0, 0.7)',   // Dark Green
            'rgba(128, 0, 128, 0.7)', // Purple
            'rgba(0, 0, 128, 0.7)',   // Navy
            'rgba(192, 192, 192, 0.7)', // Silver
            'rgba(128, 128, 128, 0.7)', // Gray
            'rgba(0, 128, 128, 0.7)', // Teal
            'rgba(255, 165, 0, 0.7)', // Orange
            'rgba(255, 192, 203, 0.7)', // Pink
            'rgba(186, 85, 211, 0.7)', // Orchid
            'rgba(0, 255, 127, 0.7)', // Spring Green
            'rgba(135, 206, 235, 0.7)', // Sky Blue
            'rgba(100, 149, 237, 0.7)', // Cornflower Blue
            'rgba(255, 228, 196, 0.7)', // Bisque
            'rgba(255, 255, 255, 0.7)', // White
            'rgba(0, 0, 0, 0.7)',       // Black
            'rgba(128, 128, 128, 0.7)', // Gray
            'rgba(255, 0, 0, 0.7)',     // Red
            'rgba(0, 255, 0, 0.7)',     // Green
            'rgba(0, 0, 255, 0.7)',     // Blue
            'rgba(255, 255, 0, 0.7)',   // Yellow
            'rgba(0, 255, 255, 0.7)',   // Cyan
            'rgba(255, 0, 255, 0.7)',   // Magenta
            'rgba(128, 0, 0, 0.7)',     // Maroon
            'rgba(128, 128, 0, 0.7)',   // Olive
            'rgba(0, 128, 0, 0.7)',     // Dark Green
            'rgba(128, 0, 128, 0.7)',   // Purple
            'rgba(0, 0, 128, 0.7)',     // Navy
            'rgba(192, 192, 192, 0.7)', // Silver
            'rgba(0, 128, 128, 0.7)',   // Teal
            'rgba(255, 165, 0, 0.7)',   // Orange
            'rgba(255, 192, 203, 0.7)', // Pink
            'rgba(186, 85, 211, 0.7)',  // Orchid
            'rgba(0, 255, 127, 0.7)',   // Spring Green
            'rgba(135, 206, 235, 0.7)', // Sky Blue
            'rgba(100, 149, 237, 0.7)', // Cornflower Blue
            'rgba(255, 228, 196, 0.7)', // Bisque
            'rgba(255, 99, 71, 0.7)',   // Tomato
            'rgba(0, 139, 139, 0.7)',   // Dark Cyan
            'rgba(255, 20, 147, 0.7)',  // Deep Pink
            'rgba(0, 191, 255, 0.7)',   // Deep Sky Blue
            'rgba(255, 105, 180, 0.7)', // Hot Pink
            'rgba(0, 250, 154, 0.7)',   // Medium Spring Green
            'rgba(70, 130, 180, 0.7)',  // Steel Blue
            'rgba(255, 250, 205, 0.7)', // Lemon Chiffon
        ];

        $between = [];
        $createdAt = explode(' - ', $filters['created_at'] ?? '');
        if (count($createdAt) === 2) {
            $between[] = Carbon::createFromFormat('d/m/Y', $createdAt[0])->format('Y-m-d').' 00:00:00';
            $between[] = Carbon::createFromFormat('d/m/Y', $createdAt[1])->format('Y-m-d').' 23:59:59';
        }

        $select = DB::table('associados')
            ->when($type === 'atendimentos', fn ($query) => $query->join('atendimentos', 'associados.id', '=', 'atendimentos.associado_id'))
            ->when($type === 'encaminhamentos', fn ($query) => $query->join('talentos', 'associados.id', '=', 'talentos.associado_id')->join('encaminhamentos', 'talentos.id', '=', 'encaminhamentos.talento_id'))
            ->when(@$filters['status'], fn ($query, $value) => $query->whereIn('associados.status', $filters['status']))
            ->when(@$filters['sexo'], fn ($query, $value) => $query->where('associados.sexo', $filters['sexo']))
            ->when(@$filters['declaracao_sexual'], fn ($query, $value) => $query->whereIn('associados.declaracao_sexual', $filters['declaracao_sexual']))
            ->when(@$filters['estado_civil'], fn ($query, $value) => $query->whereIn('associados.estado_civil', $filters['estado_civil']))
            ->when(@$filters['naturalidade_uf'], fn ($query, $value) => $query->whereIn('associados.naturalidade_uf', $filters['naturalidade_uf']))
            ->when(@$filters['religiao'], fn ($query, $value) => $query->whereIn('associados.religiao', $filters['religiao']))
            ->when(@$filters['tipo_deficiencia'], fn ($query, $value) => $query->whereIn('associados.tipo_deficiencia', $filters['tipo_deficiencia']))
            ->when(@$filters['causa_deficiencia'], fn ($query, $value) => $query->whereIn('associados.causa_deficiencia', $filters['causa_deficiencia']))
            ->when(@$filters['escolaridade'], fn ($query, $value) => $query->whereIn('associados.escolaridade', $filters['escolaridade']))
            ->when(@$filters['raca'], fn ($query, $value) => $query->whereIn('associados.raca', $filters['raca']))
            ->when(@$filters['aparelhos_utilizado'], fn ($query, $value) => $query->whereIn('associados.aparelhos_utilizado', $filters['aparelhos_utilizado']))
            ->when(@$filters['beneficios'], fn ($query, $value) => $query->whereHas('beneficios', fn ($query) => $query->whereIn('beneficios.nome', $filters['beneficios'])))
            ->when(@$filters['ocupacoes'], fn ($query, $value) => $query->whereIn('associados.ocupacoes', $filters['ocupacoes']))
            ->when($type === 'atendimentos' && @$between, fn ($query) => $query->whereBetween('atendimentos.created_at', $between))
            ->when($type === 'encaminhamentos' && @$between, fn ($query) => $query->whereBetween('encaminhamentos.created_at', $between))
            ->when(! $type && @$between, fn ($query) => $query->whereBetween('associados.created_at', $between));

        self::$total = $select->count('associados.id');

        $groupData = $select
            ->select(
                "associados.$group as group_column",
                "associados.$xAxis as x_axis_column",
                DB::raw('COUNT(*) as total')
            )
            ->groupBy("associados.$group", "associados.$xAxis")
            ->get();

        $groupTotals = [];
        $colorIndex = 0;

        foreach ($groupData as $row) {
            $groupName = $row->group_column ?? 'Sem Informação';
            $xAxisValue = $row->x_axis_column;
            $total = $row->total;

            if (! isset($groupTotals[$groupName])) {
                if ($colorIndex >= count($colors)) {
                    throw new \Exception('Not enough colors available for the number of groups.');
                }

                $groupTotals[$groupName] = [
                    'data' => array_fill_keys($labels, 0),
                    'backgroundColor' => $colors[$colorIndex],
                    'borderColor' => $colors[$colorIndex],
                ];
                $colorIndex++;
            }

            $groupTotals[$groupName]['data'][$xAxisValue] = $total;
        }

        $chartData['labels'] = $labels;

        foreach ($groupTotals as $groupName => $data) {
            $chartData['datasets'][] = [
                'label' => $groupName,
                'data' => array_values($data['data']),
                'backgroundColor' => $data['backgroundColor'],
                'borderColor' => $data['borderColor'],
            ];
        }

        return $chartData;
    }
}
