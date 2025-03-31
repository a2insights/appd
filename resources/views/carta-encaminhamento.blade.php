<x-appd :title="'Carta de Encaminhamento'" :model="$encaminhamento">

<style>
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .company-header {
        text-align: left;
        vertical-align: top;
        width: 50%;
    }

    .company-name {
        font-size: 1.1rem;
        font-weight: bold;
    }

    .notice-box {
        border: 1px solid #000;
        padding: 10px;
        background-color: #f9f9f9;
        vertical-align: top;
    }

    .document-body {
        margin: 25px 0;
        line-height: 1.6;
    }

    .candidate-info {
        margin-top: 30px;
    }

    .info-item {
        margin-bottom: 8px;
    }

    .info-label {
        font-weight: bold;
        display: inline-block;
        min-width: 120px;
    }

    strong {
        color: #2c3e50;
    }
</style>

<table class="header-table">
    <tr>
        <td class="company-header">
            <h4 class="company-name">{{ 'empresa' }}</h4>
            <h4>Recursos Humanos</h4>
        </td>
        <td class="notice-box">
            <p>O (A) Candidato(a) foi orientado(a) a levar o seu currículo, laudo médico, carteira da APPD atualizados, documentos pessoais e esta carta de apresentação.</p>
        </td>
    </tr>
</table>

<div class="document-body">
    <p>A Associação Paraense das Pessoas com Deficiência - APPD, Associação Civil de Natureza filantrópica, de fins não econômicos, de utilidade Pública Municipal, Estadual e Federal, que visa à plena participação, inclusão social e a defesa dos direitos coletivos desse seguimento da população, vem por meio deste encaminhar <strong>{{ $encaminhamento->talento->associado->nome }}</strong>.</p>

    <p>Portador do RG <strong>{{ $encaminhamento->talento->associado->rg }}</strong> a esta conceituada Empresa para que ele possa almejar uma vaga de emprego conforme sua solicitação.</p>

    <p>Desde já nossos votos de estima e agradecimento.</p>
</div>

<div class="candidate-info">
    @if($encaminhamento->talento->associado->tipo_deficiencia?->getLabel())
    <div class="info-item">
        <span class="info-label">Deficiência:</span> {{ $encaminhamento->talento->associado->tipo_deficiencia?->getLabel() }}
    </div>
    @endif

    @if($encaminhamento->talento->associado->escolaridade?->getLabel())
    <div class="info-item">
        <span class="info-label">Escolaridade:</span> {{ $encaminhamento->talento->associado->escolaridade?->getLabel() }}
    </div>
    @endif

    @if($encaminhamento->talento->associado->telefone_whatsapp)
    <div class="info-item">
        <span class="info-label">Telefone:</span> {{ $encaminhamento->talento->associado->telefone_whatsapp }}
    </div>
    @endif

    @if($encaminhamento->talento->associado->religiao?->getLabel())
    <div class="info-item">
        <span class="info-label">Religião:</span> {{ $encaminhamento->talento->associado->religiao?->getLabel() }}
    </div>
    @endif

    @if($encaminhamento->talento->associado->estado_civil?->getLabel())
    <div class="info-item">
        <span class="info-label">Estado Civil:</span> {{ $encaminhamento->talento->associado->estado_civil?->getLabel() }}
    </div>
    @endif

    @if($encaminhamento->talento->associado->bairro)
    <div class="info-item">
        <span class="info-label">Bairro:</span> {{ $encaminhamento->talento->associado->bairro }}
    </div>
    @endif

    @if($encaminhamento->vaga->cargos->isNotEmpty())
    <div class="info-item">
        <span class="info-label">Vaga:</span> {{ $encaminhamento->vaga->cargos->map(fn($cargo) => $cargo->nome)->implode(', ') }}
    </div>
    @endif
</div>

</x-appd>