<?php

namespace App\Http\Controllers;

use App\Models\Associado;
use App\Services\InfographicService;
use App\Filament\Filters\AssociadoFiltersTable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class InfographicPdfController extends Controller
{
    public function download(Request $request, InfographicService $service, \App\Services\FilterFormatterService $formatter)
    {
        $filters = $request->input('filters', []);
        
        // Reconstruct Query
        $query = Associado::query();
        $filterDefinitions = AssociadoFiltersTable::filters();

        foreach ($filterDefinitions as $filter) {
            $filter->apply(
                $query,
                $filters,
                $filter
            );
        }

        $stats = $service->getStats($query);

        // Generate Chart URLs
        $charts = [];
        if (($stats['total'] ?? 0) > 0) {
            $charts['sexo'] = $this->generateChartUrl('doughnut', array_keys($stats['sexo'] ?? []), array_values($stats['sexo'] ?? []), 'Distribuição por Sexo');
            $charts['faixa_etaria'] = $this->generateChartUrl('bar', array_keys($stats['faixa_etaria'] ?? []), array_values($stats['faixa_etaria'] ?? []), 'Faixa Etária');
            $charts['estado_civil'] = $this->generateChartUrl('pie', array_keys($stats['estado_civil'] ?? []), array_values($stats['estado_civil'] ?? []), 'Estado Civil');
            $charts['raca'] = $this->generateChartUrl('doughnut', array_keys($stats['raca'] ?? []), array_values($stats['raca'] ?? []), 'Raça/Cor');
            
            // Filter out 'Não Informado' for PDF to avoid dominance
            $dsStats = $stats['declaracao_sexual'] ?? [];
            if (isset($dsStats['Não Informado'])) {
                unset($dsStats['Não Informado']);
            }
            $charts['declaracao_sexual'] = $this->generateChartUrl('pie', array_keys($dsStats), array_values($dsStats), 'Declaração Sexual');
            
            $charts['escolaridade'] = $this->generateChartUrl('horizontalBar', array_keys($stats['escolaridade'] ?? []), array_values($stats['escolaridade'] ?? []), 'Escolaridade');
            $charts['religiao'] = $this->generateChartUrl('horizontalBar', array_keys($stats['religiao'] ?? []), array_values($stats['religiao'] ?? []), 'Religião');
            $charts['tipo_deficiencia'] = $this->generateChartUrl('doughnut', array_keys($stats['tipo_deficiencia'] ?? []), array_values($stats['tipo_deficiencia'] ?? []), 'Tipo de Deficiência');
            $charts['causa_deficiencia'] = $this->generateChartUrl('horizontalBar', array_keys($stats['causa_deficiencia'] ?? []), array_values($stats['causa_deficiencia'] ?? []), 'Causa da Deficiência');
            $charts['aparelhos_utilizado'] = $this->generateChartUrl('horizontalBar', array_keys($stats['aparelhos_utilizado'] ?? []), array_values($stats['aparelhos_utilizado'] ?? []), 'Aparelhos Utilizados (Top 10)');
            $charts['cid10'] = $this->generateChartUrl('horizontalBar', array_keys($stats['cid10'] ?? []), array_values($stats['cid10'] ?? []), 'Diagnósticos (CID-10) (Top 10)');
            $charts['ocupacoes'] = $this->generateChartUrl('horizontalBar', array_keys($stats['ocupacoes'] ?? []), array_values($stats['ocupacoes'] ?? []), 'Ocupações (Top 10)');
            $charts['beneficios'] = $this->generateChartUrl('horizontalBar', array_keys($stats['beneficios'] ?? []), array_values($stats['beneficios'] ?? []), 'Benefícios Utilizados (Top 10)');
            $charts['status'] = $this->generateChartUrl('doughnut', array_keys($stats['status'] ?? []), array_values($stats['status'] ?? []), 'Status do Associado');
            $charts['tempo_associacao'] = $this->generateChartUrl('bar', array_keys($stats['tempo_associacao'] ?? []), array_values($stats['tempo_associacao'] ?? []), 'Tempo de Associação');
            $charts['naturalidade_uf'] = $this->generateChartUrl('horizontalBar', array_keys($stats['naturalidade_uf'] ?? []), array_values($stats['naturalidade_uf'] ?? []), 'Naturalidade (UF)');
            $charts['naturalidade_municipio'] = $this->generateChartUrl('horizontalBar', array_keys($stats['naturalidade_municipio'] ?? []), array_values($stats['naturalidade_municipio'] ?? []), 'Naturalidade (Top 10 Municípios)');
            $charts['endereco_uf'] = $this->generateChartUrl('horizontalBar', array_keys($stats['endereco_uf'] ?? []), array_values($stats['endereco_uf'] ?? []), 'Estado (UF)');
            $charts['endereco_cidade'] = $this->generateChartUrl('horizontalBar', array_keys($stats['endereco_cidade'] ?? []), array_values($stats['endereco_cidade'] ?? []), 'Top 10 Cidades');
            $charts['endereco_bairro'] = $this->generateChartUrl('horizontalBar', array_keys($stats['endereco_bairro'] ?? []), array_values($stats['endereco_bairro'] ?? []), 'Top 10 Bairros');
        }

        // Format filters for display
        $formattedFilters = $formatter->format($filters);

        $pdf = Pdf::loadView('pdf.infographic', [
            'stats' => $stats,
            'charts' => $charts,
            'filters' => $formattedFilters
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('infografico-segmentacao.pdf');
    }

    private function generateChartUrl($type, $labels, $data, $title)
    {
        if (empty($data)) {
            return null;
        }

        $config = [
            'type' => $type,
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Associados',
                    'data' => $data,
                    'backgroundColor' => $this->getColors($type, count($data)),
                ]]
            ],
            'options' => [
                'plugins' => [
                    'legend' => [
                        'display' => in_array($type, ['doughnut', 'pie']),
                        'position' => 'right',
                        'align' => 'start'
                    ],
                    'title' => [
                        'display' => false,
                        'text' => $title
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => '#000',
                        'anchor' => 'end',
                        'align' => 'start',
                        'offset' => -10,
                        'font' => ['weight' => 'bold']
                    ]
                ]
            ]
        ];

        // Encode and build URL
        $chartConfig = json_encode($config);
        // Increased resolution for landscape full page
        return 'https://quickchart.io/chart?c=' . urlencode($chartConfig) . '&w=1200&h=600&devicePixelRatio=2';
    }

    private function getColors($type, $count)
    {
        $colors = [
            '#3b82f6', '#ec4899', '#10b981', '#f59e0b', '#8b5cf6', 
            '#6366f1', '#ef4444', '#f97316', '#84cc16', '#06b6d4'
        ];

        if (in_array($type, ['doughnut', 'pie'])) {
            return array_slice($colors, 0, $count);
        }

        return '#3b82f6'; // Single color for bars
    }
}
