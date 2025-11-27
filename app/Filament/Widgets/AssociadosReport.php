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

        if (! $xAxis) {
            return [];
        }

        $displayYAxis = !empty($group);

        return $this->getOptionsData($xAxis, $displayYAxis, empty($group));
    }

    public function getOptionsData($xAxis, $displayYAxis = true, $showIndividualBarLabelsInLegend = false): array
    {
        $legendDisplay = true;


        return [
            'plugins' => [
                'legend' => [
                    'display' => $legendDisplay,
                    'position' => 'bottom',
                    'align' => 'center',
                    'labels' => [
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold'
                        ],
                        'color' => '#333'
                    ]
                ],
                'datalabels' => [
                    'display' => true,
                    'font' => [
                        'size' => 14,
                        'weight' => 'bold',
                        'lineHeight' => 1.2
                    ],
                    'color' => '#FFF',
                    'backgroundColor' => 'rgba(0, 0, 0, 0.5)',
                    'borderRadius' => 2,
                    'padding' => [
                        'top' => 3,
                        'right' => 12,
                        'bottom' => 3,
                        'left' => 12
                    ],
                    'textStrokeColor' => 'rgba(255, 255, 255, 0.8)',
                    'textStrokeWidth' => 1,
                    'textShadowColor' => 'rgba(0, 0, 0, 0.5)',
                    'textShadowBlur' => 5,
                    'align' => 'center',
                    'anchor' => 'start'
                ],
            ],
            'options' => [
                'barThickness' => 20,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'stacked' => false,
                        'title' => [
                            'display' => $displayYAxis,
                            'text' => 'Quantidade',
                        ],
                        'display' => $displayYAxis,
                    ],
                    'x' => [
                        'stacked' => false,
                        'title' => [
                            'display' => true,
                            'text' => $xAxis,
                        ],
                        'ticks' => [
                            'display' => $showIndividualBarLabelsInLegend ? false : true,
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

        if (! $xAxis) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        if (empty($group)) {
            return $this->generateTotalCountDatasetsAndLabels($xAxis, $filters, $this->reportType);
        }

        return $this->generateGroupedDatasetsAndLabels($xAxis, $group, $filters, $this->reportType);
    }

    private function getFilter(string $key, $default = null)
    {
        return $this->filters[$key] ?? $default;
    }

    public function generateGroupedDatasetsAndLabels(string $xAxis, string $group, ?array $filters = null, ?string $type = null): array
    {
        $chartData = ['labels' => [], 'datasets' => []];

        $labels = DB::table('associados')
            ->select("associados.$xAxis")
            ->groupBy("associados.$xAxis")
            ->pluck($xAxis)
            ->toArray();

        $colors = $this->getChartColors();

        $between = $this->getDateFilterRange($filters);

        $select = $this->getBaseQuery($type, $filters, $between);

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
                    $colorIndex = 0;
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

    public function generateTotalCountDatasetsAndLabels(string $xAxis, ?array $filters = null, ?string $type = null): array
    {
        $chartData = ['labels' => [], 'datasets' => []];

        $labels = DB::table('associados')
            ->select("associados.$xAxis")
            ->groupBy("associados.$xAxis")
            ->pluck($xAxis)
            ->toArray();

        $colors = $this->getChartColors();

        $between = $this->getDateFilterRange($filters);

        $select = $this->getBaseQuery($type, $filters, $between);

        $totalCounts = $select
            ->select(
                "associados.$xAxis as x_axis_column",
                DB::raw('COUNT(*) as total')
            )
            ->groupBy("associados.$xAxis")
            ->pluck('total', 'x_axis_column')
            ->toArray();

        self::$total = array_sum($totalCounts);

        $colorIndex = 0;
        foreach ($labels as $label) {
            $dataValue = $totalCounts[$label] ?? 0;

            if ($colorIndex >= count($colors)) {
                $colorIndex = 0;
            }

            $chartData['datasets'][] = [
                'label' => $label,
                'data' => [$dataValue],
                'backgroundColor' => $colors[$colorIndex],
                'borderColor' => str_replace('0.7)', '1)', $colors[$colorIndex]),
            ];
            $colorIndex++;
        }

        $chartData['labels'] = [''];

        return $chartData;
    }

    private function getBaseQuery(?string $type = null, ?array $filters = null, ?array $between = null)
    {
        return DB::table('associados')
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
    }

    private function getDateFilterRange(?array $filters): array
    {
        $between = [];
        $filterValue = $filters['date_range'] ?? $filters['created_at'] ?? '';
        $createdAt = explode(' - ', $filterValue);
        if (count($createdAt) === 2) {
            $between[] = Carbon::createFromFormat('d/m/Y', $createdAt[0])->format('Y-m-d').' 00:00:00';
            $between[] = Carbon::createFromFormat('d/m/Y', $createdAt[1])->format('Y-m-d').' 23:59:59';
        }
        return $between;
    }

    private function getChartColors(): array
    {
        return [
            'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
            'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
            'rgba(255, 0, 0, 0.7)', 'rgba(0, 255, 0, 0.7)', 'rgba(0, 0, 255, 0.7)',
            'rgba(255, 255, 0, 0.7)', 'rgba(0, 255, 255, 0.7)', 'rgba(255, 0, 255, 0.7)',
            'rgba(128, 0, 0, 0.7)', 'rgba(128, 128, 0, 0.7)', 'rgba(0, 128, 0, 0.7)',
            'rgba(128, 0, 128, 0.7)', 'rgba(0, 0, 128, 0.7)', 'rgba(192, 192, 192, 0.7)',
            'rgba(128, 128, 128, 0.7)', 'rgba(0, 128, 128, 0.7)', 'rgba(255, 165, 0, 0.7)',
            'rgba(255, 192, 203, 0.7)', 'rgba(186, 85, 211, 0.7)', 'rgba(0, 255, 127, 0.7)',
            'rgba(135, 206, 235, 0.7)', 'rgba(100, 149, 237, 0.7)', 'rgba(255, 228, 196, 0.7)',
            'rgba(255, 255, 255, 0.7)', 'rgba(0, 0, 0, 0.7)', 'rgba(128, 128, 128, 0.7)',
            'rgba(255, 0, 0, 0.7)', 'rgba(0, 255, 0, 0.7)', 'rgba(0, 0, 255, 0.7)',
            'rgba(255, 255, 0, 0.7)', 'rgba(0, 255, 255, 0.7)', 'rgba(255, 0, 255, 0.7)',
            'rgba(128, 0, 0, 0.7)', 'rgba(128, 128, 0, 0.7)', 'rgba(0, 128, 0, 0.7)',
            'rgba(128, 0, 128, 0.7)', 'rgba(0, 0, 128, 0.7)', 'rgba(192, 192, 192, 0.7)',
            'rgba(0, 128, 128, 0.7)', 'rgba(255, 165, 0, 0.7)', 'rgba(255, 192, 203, 0.7)',
            'rgba(186, 85, 211, 0.7)', 'rgba(0, 255, 127, 0.7)', 'rgba(135, 206, 235, 0.7)',
            'rgba(100, 149, 237, 0.7)', 'rgba(255, 228, 196, 0.7)', 'rgba(255, 99, 71, 0.7)',
            'rgba(0, 139, 139, 0.7)', 'rgba(255, 20, 147, 0.7)', 'rgba(0, 191, 255, 0.7)',
            'rgba(255, 105, 180, 0.7)', 'rgba(0, 250, 154, 0.7)', 'rgba(70, 130, 180, 0.7)',
            'rgba(255, 250, 205, 0.7)',
        ];
    }
}
