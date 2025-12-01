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
            height: 100%; /* Ensure body takes full height */
        }
        html { height: 100%; } /* Ensure html takes full height */
        
        /* Ensure font inheritance for tables and other elements in domPDF */
        table, td, th, div, p, span, h1, h2, h3, h4, h5, h6, li, a {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Header & Footer */
        /* Header removed as it is now conditional */
        
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
            /* Removed padding-top: 140px; */
            padding-bottom: 50px; /* Space for footer */
            padding-left: 40px;
            padding-right: 40px;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Add spacing below header */
            border-bottom: 2px solid #f1f5f9; /* Moved border here */
            padding-bottom: 10px;
        }
        .header-left {
            width: 65%;
            vertical-align: top;
            text-align: left; /* Ensure alignment */
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

        /* Official Header */
        .official-header {
            margin-bottom: 30px;
            position: relative;
            text-align: left; /* Reset alignment */
            margin-left: 20px;
            margin-right: 20px;
        }
        .official-header img {
            width: 70px;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
        }
        .official-header-text {
            margin-left: 80px;
            font-size: 10px;
            line-height: 1.3;
            color: #334155;
            text-align: left;
        }
    </style>
</head>
<body>

    {{-- Header removed --}}

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

    {{-- Page 1: Cover --}}
    <div class="page-container bg-white z-20">
        <table class="content-table">
            <tr>
                <td class="content-cell align-middle text-center p-0">
                    
                    {{-- Spacer Div (Gap) --}}
                    <div style="height: 100px;"></div>

                    {{-- Logo & Official Text Group --}}
                    <div class="mb-12 mt-10" style="text-align: center; margin-left: 40px; margin-right: 40px;">
                        <img src="{{ public_path('img/logo.svg') }}" alt="Logo APPD" style="width: 80px; display: inline-block; margin-bottom: 15px;">
                        {{-- Spacer Div (Gap) --}}
                        <div style="height: 50px;"></div>
                        <div style="text-align: center;">
                            <p style="font-size: 11px; font-weight: bold; margin: 0; line-height: 1.4; color: #333;">
                                Associação Paraense das Pessoas com Deficiência - A.P.P.D. <br>
                                <span style="font-weight: normal;">
                                    Fundada em 26.11.1981, declarada de utilidade pública Municipal - Lei nº 7.549 de 18.12.91<br>
                                    Estadual Lei nº 5.565 de 27.10.89 - Federal - Lei nº 91 - Decreto 50.517 de 17.12.91<br>
                                    CNAS nº 28985.000439/94-79 - Filantropia: Resolução 040 de 09.04.98<br>
                                    CNPJ: 04.704.797/0001-69
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Separator --}}
                    <div style="border-bottom: 2px solid #3b82f6; width: 60px; margin: 40px auto;"></div>
                    
                    {{-- Title Section --}}
                    <div class="mb-10">
                        <h2 class="text-sm text-slate-500 uppercase mb-2" style="letter-spacing: 2px;">Relatório Oficial</h2>
                        <h1 class="text-3xl text-slate-800 font-bold uppercase mb-4" style="font-size: 36px; letter-spacing: 1px;">
                            Segmentação de Associados
                        </h1>
                        <div class="text-sm text-slate-400">
                            Gerado em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}
                        </div>
                    </div>

                    {{-- Stats Box --}}
                    <div class="inline-block bg-gray-50 border rounded p-5 mb-8" style="min-width: 250px;">
                        <span class="block text-xs text-slate-500 uppercase font-bold mb-1">Total de Registros</span>
                        <span class="block text-3xl text-blue-500 font-bold">
                            {{ number_format($stats['total'] ?? 0, 0, ',', '.') }}
                        </span>
                        <span class="block text-xs text-slate-400 mt-1">Associados Encontrados</span>
                    </div>

                    {{-- Bottom Decoration --}}
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 10px; background-color: #3b82f6;"></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Page 2: Context / Filters --}}
    <div class="page-container">
        <table class="content-table">
            <tr>
                <td class="content-cell pt-10 align-top text-left">
                    <h2 class="text-xl text-slate-800 mb-5 border-b-2 pb-2">
                        Parâmetros do Relatório
                    </h2>
                    
                    <div class="bg-gray-50 p-5 rounded border">
                        <h3 class="text-base text-slate-700 mb-4 uppercase">Filtros Aplicados</h3>
                        
                        {{-- Use Floats instead of Flexbox for domPDF --}}
                        <div class="w-full">
                            @forelse($filters as $key => $filter)
                                <div class="mb-4 w-half float-left avoid-break">
                                    <span class="block text-xs text-slate-500 font-semibold uppercase">
                                        {{ $filter['label'] }}
                                    </span>
                                    <span class="block text-sm text-slate-900 font-medium">
                                        {{ $filter['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="w-full text-center text-slate-400 italic">
                                    Nenhum filtro aplicado. Relatório geral de todos os associados.
                                </div>
                            @endforelse
                            <div class="clear-both"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Page 3: Table of Contents (Sumário) --}}
    <div class="page-container">
        <table class="content-table">
            <tr>
                <td class="content-cell pt-5 align-top text-left">
                    <h2 class="text-xl text-slate-800 mb-5 border-b-2 pb-2">
                        Sumário dos Gráficos
                    </h2>
                    
                    @php
                        // Split sections into two columns for table layout
                        $allSections = $sections;
                        $chunkSize = ceil(count($allSections) / 2);
                        $chunks = array_chunk($allSections, $chunkSize, true);
                        $leftColumn = $chunks[0] ?? [];
                        $rightColumn = $chunks[1] ?? [];
                    @endphp

                    <table class="w-full" style="border-collapse: collapse;">
                        <tr>
                            {{-- Left Column --}}
                            <td class="w-half align-top">
                                @foreach($leftColumn as $sectionName => $chartKeys)
                                    @php
                                        $hasCharts = false;
                                        foreach($chartKeys as $key) {
                                            if(!empty($charts[$key])) $hasCharts = true;
                                        }
                                    @endphp
                                    
                                    @if($hasCharts)
                                        <div class="mb-5">
                                            <h4 class="text-sm text-blue-500 uppercase mb-2 font-bold">
                                                {{ $sectionName }}
                                            </h4>
                                            <ul class="list-none p-0 m-0">
                                                @foreach($chartKeys as $key)
                                                    @if(!empty($charts[$key]))
                                                        <li class="mb-1 text-xs text-slate-600 border-b-dashed pb-2">
                                                            {{ $key === 'cid10' ? 'Diagnósticos (CID-10)' : ($key === 'naturalidade_municipio' ? 'Top 10 Municípios' : ucwords(str_replace('_', ' ', $key))) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                            
                            {{-- Spacer --}}
                            <td style="width: 4%;"></td>

                            {{-- Right Column --}}
                            <td class="w-half align-top">
                                @foreach($rightColumn as $sectionName => $chartKeys)
                                    @php
                                        $hasCharts = false;
                                        foreach($chartKeys as $key) {
                                            if(!empty($charts[$key])) $hasCharts = true;
                                        }
                                    @endphp
                                    
                                    @if($hasCharts)
                                        <div class="mb-5">
                                            <h4 class="text-sm text-blue-500 uppercase mb-2 font-bold">
                                                {{ $sectionName }}
                                            </h4>
                                            <ul class="list-none p-0 m-0">
                                                @foreach($chartKeys as $key)
                                                    @if(!empty($charts[$key]))
                                                        <li class="mb-1 text-xs text-slate-600 border-b-dashed pb-2">
                                                            {{ $key === 'cid10' ? 'Diagnósticos (CID-10)' : ($key === 'naturalidade_municipio' ? 'Top 10 Municípios' : ucwords(str_replace('_', ' ', $key))) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- Charts Pages (Page 4+) --}}
    @foreach($flatCharts as $chart)
        <div class="page-container">
            <table class="content-table">
                <tr>
                    <td class="content-cell pt-5 align-top">
                        {{-- Header for Chart Pages --}}
                        <table class="header-table mb-5 border-b-2 pb-2">
                            <tr>
                                <td class="header-left text-left">
                                    <h1>Relatório de Segmentação</h1>
                                    <div class="filters-container" style="border: none; background: transparent; padding: 0;">
                                        {{-- Filters removed as requested --}}
                                    </div>
                                </td>
                                <td class="header-right text-right">
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
