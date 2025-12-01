<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Infográfico de Segmentação</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('{{ public_path("fonts/Poppins-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ public_path("fonts/Poppins-Medium.ttf") }}') format('truetype');
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ public_path("fonts/Poppins-Bold.ttf") }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        /* Header & Footer */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px; /* Adjusted height */
            background-color: #ffffff;
            border-bottom: 2px solid #f1f5f9;
            padding: 15px 40px;
            z-index: 1000;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            padding: 5px 40px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
            line-height: 30px;
        }

        /* Content Area */
        .page-container {
            width: 100%;
            height: 100%;
            page-break-after: always;
            position: relative;
        }
        
        .content-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .content-cell {
            vertical-align: middle;
            text-align: center;
            padding-top: 140px; /* Space for header */
            padding-bottom: 50px; /* Space for footer */
            padding-left: 40px;
            padding-right: 40px;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-left {
            width: 65%;
            vertical-align: top;
        }
        .header-right {
            width: 35%;
            vertical-align: top;
            text-align: right;
        }
        
        h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Filters Section */
        .filters-container {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
            max-height: 42px; /* Limit height */
            overflow: hidden;
        }
        .filters-label {
            font-weight: 700;
            color: #334155;
            margin-bottom: 3px;
            text-transform: uppercase;
            font-size: 9px;
            display: block;
        }
        .filter-item {
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }
        .filter-key {
            font-weight: 500;
            color: #64748b;
        }
        .filter-val {
            font-weight: 700;
            color: #0369a1;
        }

        /* Total Badge */
        .total-box {
            display: inline-block;
            text-align: right;
        }
        .total-number {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
            line-height: 1;
        }
        .total-text {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
        }
        .date-info {
            font-size: 9px;
            color: #cbd5e1;
            margin-top: 6px;
            font-weight: 400;
        }

        /* Chart Image */
        .chart-img {
            max-width: 95%;
            max-height: 480px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        
        .section-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: block;
        }
        
        .page-number:before {
            content: "Página " counter(page);
        }
        
        .page-container:last-child {
            page-break-after: auto;
        }
    </style>
</head>
<body>

    <header>
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <h1>Relatório de Segmentação</h1>
                    <div class="filters-container">
                        <span class="filters-label">Filtros Aplicados:</span>
                        @forelse($filters as $key => $filter)
                            <span class="filter-item">
                                <span class="filter-key">{{ $filter['label'] }}:</span>
                                <span class="filter-val">{{ \Illuminate\Support\Str::limit($filter['value'], 30) }}</span>
                            </span>
                        @empty
                            <span style="font-style: italic; color: #94a3b8;">Todos os associados</span>
                        @endforelse
                    </div>
                </td>
                <td class="header-right">
                    <div class="total-box">
                        <div class="total-number">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</div>
                        <div class="total-text">Total Encontrado</div>
                    </div>
                    <div class="date-info">
                        Gerado em {{ now()->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <span class="page-number"></span> | A2 Insights
    </footer>

    @php
        $sections = [
            'Demografia' => ['sexo', 'faixa_etaria', 'estado_civil'],
            'Identificação Social' => ['raca', 'declaracao_sexual', 'escolaridade', 'religiao'],
            'Saúde e Deficiência' => ['tipo_deficiencia', 'causa_deficiencia', 'aparelhos_utilizado', 'cid10'],
            'Profissional e Benefícios' => ['ocupacoes', 'beneficios'],
            'Dados da Associação' => ['status', 'tempo_associacao'],
            'Naturalidade' => ['naturalidade_uf', 'naturalidade_municipio'],
            'Localização Atual' => ['endereco_uf', 'endereco_cidade', 'endereco_bairro'],
        ];

        $flatCharts = [];
        foreach($sections as $sectionName => $chartKeys) {
            foreach($chartKeys as $key) {
                if(!empty($charts[$key])) {
                    $flatCharts[] = [
                        'key' => $key,
                        'section' => $sectionName,
                        'title' => $key === 'cid10' ? 'Diagnósticos (CID-10)' : ($key === 'naturalidade_municipio' ? 'Top 10 Municípios' : ucwords(str_replace('_', ' ', $key))),
                        'url' => $charts[$key]
                    ];
                }
            }
        }
    @endphp

    @foreach($flatCharts as $chart)
        <div class="page-container">
            <table class="content-table">
                <tr>
                    <td class="content-cell">
                        <div class="section-label">
                            {{ $chart['section'] }} &bull; {{ $chart['title'] }}
                        </div>
                        <img src="{{ $chart['url'] }}" class="chart-img">
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

</body>
</html>
