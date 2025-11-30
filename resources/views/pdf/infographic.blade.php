<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Infográfico de Segmentação</title>
    <style>
        @page { margin: 20px; size: A4; }
        body { font-family: sans-serif; color: #333; font-size: 12px; }
        .header { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1e3a8a; }
        .header p { margin: 5px 0 0; color: #666; font-size: 10px; }
        .total-box { float: right; text-align: right; margin-top: -40px; }
        .total-val { font-size: 24px; font-weight: bold; color: #2563eb; }
        .section-title { font-size: 14px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-top: 20px; margin-bottom: 10px; color: #1f2937; clear: both; }
        
        .grid-container { width: 100%; margin-bottom: 10px; }
        .chart-box { width: 48%; float: left; margin-bottom: 20px; page-break-inside: avoid; }
        .chart-box:nth-child(even) { float: right; }
        .chart-title { font-weight: bold; margin-bottom: 5px; font-size: 11px; color: #4b5563; }
        .chart-img { width: 100%; height: auto; border: 1px solid #eee; border-radius: 5px; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
        
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; background: #eff6ff; color: #1e40af; font-size: 10px; margin-right: 5px; margin-bottom: 5px; border: 1px solid #dbeafe; }
        .badge-count { font-weight: bold; margin-left: 3px; }
    </style>
</head>
<body>
    
    <div class="header">
        <h2>Relatório de Segmentação</h2>
        <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        <div class="total-box">
            <div style="font-size: 10px; text-transform: uppercase; color: #666;">Total Encontrado</div>
            <div class="total-val">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</div>
            <div style="font-size: 10px; color: #999;">Associados</div>
        </div>
    </div>

    <!-- Demografia -->
    <div class="grid-container clearfix">
        @if(!empty($charts['sexo']))
        <div class="chart-box">
            <div class="chart-title">Distribuição por Sexo</div>
            <img src="{{ $charts['sexo'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['faixa_etaria']))
        <div class="chart-box">
            <div class="chart-title">Faixa Etária</div>
            <img src="{{ $charts['faixa_etaria'] }}" class="chart-img">
        </div>
        @endif
        
        <div class="clearfix"></div>

        @if(!empty($charts['estado_civil']))
        <div class="chart-box">
            <div class="chart-title">Estado Civil</div>
            <img src="{{ $charts['estado_civil'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Identificação Social -->
    <div class="section-title">Identificação Social</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['raca']))
        <div class="chart-box">
            <div class="chart-title">Raça/Cor</div>
            <img src="{{ $charts['raca'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['declaracao_sexual']))
        <div class="chart-box">
            <div class="chart-title">Declaração Sexual</div>
            <img src="{{ $charts['declaracao_sexual'] }}" class="chart-img">
        </div>
        @endif

        <div class="clearfix"></div>

        @if(!empty($charts['escolaridade']))
        <div class="chart-box">
            <div class="chart-title">Escolaridade</div>
            <img src="{{ $charts['escolaridade'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['religiao']))
        <div class="chart-box">
            <div class="chart-title">Religião</div>
            <img src="{{ $charts['religiao'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Saúde e Deficiência -->
    <div class="section-title">Saúde e Deficiência</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['tipo_deficiencia']))
        <div class="chart-box">
            <div class="chart-title">Tipo de Deficiência</div>
            <img src="{{ $charts['tipo_deficiencia'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['causa_deficiencia']))
        <div class="chart-box">
            <div class="chart-title">Causa da Deficiência</div>
            <img src="{{ $charts['causa_deficiencia'] }}" class="chart-img">
        </div>
        @endif

        <div class="clearfix"></div>

        @if(!empty($charts['aparelhos_utilizado']))
        <div class="chart-box">
            <div class="chart-title">Aparelhos Utilizados (Top 10)</div>
            <img src="{{ $charts['aparelhos_utilizado'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['cid10']))
        <div class="chart-box">
            <div class="chart-title">Diagnósticos (CID-10) (Top 10)</div>
            <img src="{{ $charts['cid10'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Profissional e Benefícios -->
    <div class="section-title">Profissional e Benefícios</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['ocupacoes']))
        <div class="chart-box">
            <div class="chart-title">Ocupações (Top 10)</div>
            <img src="{{ $charts['ocupacoes'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['beneficios']))
        <div class="chart-box">
            <div class="chart-title">Benefícios Utilizados (Top 10)</div>
            <img src="{{ $charts['beneficios'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Dados da Associação -->
    <div class="section-title">Dados da Associação</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['status']))
        <div class="chart-box">
            <div class="chart-title">Status do Associado</div>
            <img src="{{ $charts['status'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['tempo_associacao']))
        <div class="chart-box">
            <div class="chart-title">Tempo de Associação</div>
            <img src="{{ $charts['tempo_associacao'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Naturalidade -->
    <div class="section-title">Naturalidade</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['naturalidade_uf']))
        <div class="chart-box">
            <div class="chart-title">Naturalidade (UF)</div>
            <img src="{{ $charts['naturalidade_uf'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['naturalidade_municipio']))
        <div class="chart-box">
            <div class="chart-title">Naturalidade (Top 10 Municípios)</div>
            <img src="{{ $charts['naturalidade_municipio'] }}" class="chart-img">
        </div>
        @endif
    </div>

    <!-- Localização Atual -->
    <div class="section-title">Localização Atual</div>
    <div class="grid-container clearfix">
        @if(!empty($charts['endereco_uf']))
        <div class="chart-box">
            <div class="chart-title">Estado (UF)</div>
            <img src="{{ $charts['endereco_uf'] }}" class="chart-img">
        </div>
        @endif

        @if(!empty($charts['endereco_cidade']))
        <div class="chart-box">
            <div class="chart-title">Top 10 Cidades</div>
            <img src="{{ $charts['endereco_cidade'] }}" class="chart-img">
        </div>
        @endif

        <div class="clearfix"></div>

        @if(!empty($charts['endereco_bairro']))
        <div class="chart-box" style="width: 100%;">
            <div class="chart-title">Top 10 Bairros</div>
            <img src="{{ $charts['endereco_bairro'] }}" class="chart-img" style="max-height: 300px; object-fit: contain;">
        </div>
        @endif
    </div>

</body>
</html>
