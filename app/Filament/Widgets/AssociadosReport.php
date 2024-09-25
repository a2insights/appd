<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssociadosReport extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Associados';

    protected static ?string $maxHeight = '450px';

    protected static ?int $total = 0;

    protected function getType(): string
    {
        return 'bar';
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'align' => 'center',
                ],
                'title' => [
                    'display' => true,
                    'text' => Str::title($this->getFilter('xAxis')).' dos Associados'.' por '.Str::title($this->getFilter('group')),
                ],
                'subtitle' => [
                    'display' => true,
                    'text' => 'Total de Associados: '.self::$total,
                    'font' => [
                        'size' => 16,
                    ],
                    'padding' => [
                        'bottom' => 10,
                    ],
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
                            'text' => Str::title($this->getFilter('xAxis')),
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $xAxis = $this->getFilter('xAxis');
        $group = $this->getFilter('group'); // Treat group as a column name

        if (! $xAxis || ! $group) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return $this->generateDatasetsAndLabels($xAxis, $group);
    }

    private function getFilter(string $key, $default = null)
    {
        return $this->filters[$key] ?? $default;
    }

    private function generateDatasetsAndLabels(string $xAxis, string $group): array
    {
        $chartData = ['labels' => [], 'datasets' => []];

        // Fetch labels
        $labels = DB::table('associados')
            ->select($xAxis)
            ->groupBy($xAxis)
            ->pluck($xAxis)
            ->toArray();

        // Define a larger set of colors for the dataset
        $colors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 99, 132, 0.4)',
            'rgba(54, 162, 235, 0.4)',
            'rgba(255, 206, 86, 0.4)',
            'rgba(75, 192, 192, 0.4)',
            'rgba(153, 102, 255, 0.4)',
            'rgba(255, 159, 64, 0.4)',
            'rgba(255, 0, 0, 0.5)',  // Red
            'rgba(0, 255, 0, 0.5)',  // Green
            'rgba(0, 0, 255, 0.5)',  // Blue
            'rgba(255, 255, 0, 0.5)', // Yellow
            'rgba(0, 255, 255, 0.5)', // Cyan
            'rgba(255, 0, 255, 0.5)', // Magenta
            'rgba(128, 0, 0, 0.5)',   // Maroon
            'rgba(128, 128, 0, 0.5)', // Olive
            'rgba(0, 128, 0, 0.5)',   // Dark Green
            'rgba(128, 0, 128, 0.5)', // Purple
            'rgba(0, 0, 128, 0.5)',   // Navy
            'rgba(192, 192, 192, 0.5)', // Silver
            'rgba(128, 128, 128, 0.5)', // Gray
            'rgba(0, 128, 128, 0.5)', // Teal
            'rgba(255, 165, 0, 0.5)', // Orange
            'rgba(255, 192, 203, 0.5)', // Pink
            'rgba(186, 85, 211, 0.5)', // Orchid
            'rgba(0, 255, 127, 0.5)', // Spring Green
            'rgba(135, 206, 235, 0.5)', // Sky Blue
            'rgba(100, 149, 237, 0.5)', // Cornflower Blue
            'rgba(255, 228, 196, 0.5)', // Bisque
        ];

        // Shuffle the colors array to randomize the order
        shuffle($colors);

        // Generate data for each distinct value in the group column
        $groupData = DB::table('associados')
            ->select($group, $xAxis, DB::raw('COUNT(*) as total'))
            ->groupBy($group, $xAxis)
            ->get();

        // Prepare dataset for each unique group value
        $groupTotals = [];
        $colorIndex = 0; // Index to keep track of the assigned color

        foreach ($groupData as $row) {
            $groupName = $row->$group; // Get the value of the group column
            $total = $row->total;

            // Initialize the dataset for the group if it doesn't exist
            if (! isset($groupTotals[$groupName])) {
                // Prevent exceeding the colors array
                if ($colorIndex >= count($colors)) {
                    throw new \Exception('Not enough colors available for the number of groups.');
                }

                $groupTotals[$groupName] = [
                    'data' => array_fill_keys($labels, 0), // Initialize data for all labels
                    'backgroundColor' => $colors[$colorIndex],
                    'borderColor' => $colors[$colorIndex], // Use the same color for borders
                ];

                $colorIndex++; // Move to the next color
            }

            // Populate data based on xAxis label
            $groupTotals[$groupName]['data'][$row->$xAxis] = $total;
        }

        // Construct the chart data
        foreach ($labels as $label) {
            $chartData['labels'][] = $label;
        }

        foreach ($groupTotals as $groupName => $data) {
            $chartData['datasets'][] = [
                'label' => $groupName,
                'data' => array_values($data['data']), // Get data values in the correct order
                'backgroundColor' => $data['backgroundColor'],
                'borderColor' => $data['borderColor'], // Keep border color the same as background color
            ];
        }

        self::$total = DB::table('associados')->count();

        return $chartData;
    }
}
