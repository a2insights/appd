<x-appd :title="'Ficha de Associado'" :model="$record" :hide-code="true">
<style>
    .document-body {
        font-family: Arial, sans-serif;
        font-size: 1rem;
        color: #2e3a59;
        line-height: 1.3;
        background: #ffffff;
        padding: 20px 25px;
        border-radius: 8px;
        max-width: 800px;
        margin: 20px auto;
        margin-top: 0;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
        word-wrap: break-word;
        /* Add overflow to contain the floated element */
        overflow: hidden;
    }

    .photo-container {
        width: 110px;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        border: 1.5px solid #bbb;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        /* Changed for floating */
        float: right;
        margin-left: 20px; /* Space between photo and text */
        margin-bottom: 18px; /* Maintain bottom margin */
        background-color: #f9f9f9;
    }

    .photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .section-group {
        margin-bottom: 18px;
    }

    .info-row {
        display: flex;
        gap: 25px;
        flex-wrap: nowrap;
        align-items: center;
        margin-bottom: 8px;
    }

    .info-label {
        flex: 0 0 130px;
        font-weight: bold;
        color: #1b264d;
        font-size: 0.9rem;
        min-width: 130px;
        user-select: none;
    }

    .info-value {
        color: #53607a;
        font-size: 1rem;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .multiline .info-value {
        white-space: normal;
        font-weight: normal;
        color: #53607a;
        font-size: 0.8rem;
        overflow: visible;
    }

    /* Cabeçalho do grupo */
    .header-group {
        display: flex;
        gap: 25px;
        margin-bottom: 12px;
        align-items: baseline;
    }

    .header-group .info-label {
        flex: 0 0 80px;
        min-width: 80px;
    }

    .header-group .info-value {
        flex: 1;
        white-space: nowrap;
    }

    .document-group {
        margin-top: 6px;
        font-size: 0.9rem;
        color: #44516f;
        font-weight: normal;
    }

    .deficiency-info {
        border-left: 5px solid #3a6ff0;
        background-color: #e6efff;
        padding: 12px 18px;
        color: #1a2f70;
        font-weight: bold;
        font-size: 0.9rem;
        margin-top: 15px;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .container {
        padding-top: 0 ! important;
    }
</style>

<div class="document-body">
    @if($record->fotoUrl)
    <div class="photo-container" title="Foto do Associado">
        <img src="{{ $record->fotoUrl }}" alt="Foto do associado" class="photo">
    </div>
    @endif

    <div class="content-wrapper">
        <div class="section-group header-group">
            <div class="info-row">
                <span class="info-label">Nome Completo</span>
                <span class="info-value" title="{{ $record->nome }}">{{ $record->nome }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Código</span>
                <span class="info-value" title="{{ $record->id }}">{{ $record->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">{{ $record->status->getLabel() }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sexo</span>
                <span class="info-value">{{ $record->sexo->getLabel() }}</span>
            </div>
        </div>

        @if($record->nome_social)
        <div class="section-group info-row">
            <span class="info-label">Nome Social</span>
            <span class="info-value" title="{{ $record->nome_social }}">{{ $record->nome_social }}</span>
        </div>
        @endif

        <div class="section-group info-row">
            <span class="info-label">Data Nasc.</span>
            <span class="info-value">{{ $record->data_nascimento?->format('d/m/Y') }}</span>
        </div>

        <div class="section-group info-row">
            <span class="info-label">Data de Assoc.</span>
            <span class="info-value">{{ $record->data_associacao?->format('d/m/Y') }}</span>
        </div>

        @if($record->declaracao_sexual)
        <div class="section-group info-row">
            <span class="info-label">Decl. Sexual</span>
            <span class="info-value">{{ $record->declaracao_sexual->getLabel() }}</span>
        </div>
        @endif

        <div class="section-group info-row" style="flex-direction: column; align-items: flex-start;">
            <span class="info-label">Documento / Órgão Expedidor</span>
            <span class="info-value" title="{{ $record->getDocumento() }}">
                {{ $record->getDocumento() }}
            </span>
            @if($record->rg && $record->orgao_expedidor)
            <span class="document-group">
                {{ $record->orgao_expedidor->getLabel() }} / {{ $record->orgao_expedidor_uf?->getLabel() }}
            </span>
            @endif
        </div>

        <div class="section-group info-row">
            <span class="info-label">Estado Civil</span>
            <span class="info-value">{{ $record->estado_civil->getLabel() }}</span>
        </div>

        <div class="section-group info-row">
            <span class="info-label">Naturalidade</span>
            <span class="info-value" title="{{ $record->naturalidade_uf?->getLabel() }}">
                {{ $record->naturalidade_uf?->getLabel() }}
                @if($record->naturalidade_municipio_ibge) - {{ $record->naturalidade_municipio_ibge }} @endif
            </span>
        </div>

        <div class="section-group info-row">
            <span class="info-label">Mãe / Pai</span>
            <span class="info-value" title="{{ $record->mae ?? '' }} / {{ $record->pai ?? '' }}">
                {{ $record->mae ?? 'Não informado' }} / {{ $record->pai ?? 'Não informado' }}
            </span>
        </div>

        @if($record->religiao)
        <div class="section-group info-row">
            <span class="info-label">Religião</span>
            <span class="info-value">{{ $record->religiao->getLabel() }}</span>
        </div>
        @endif

        @if($record->raca)
        <div class="section-group info-row">
            <span class="info-label">Raça/Cor</span>
            <span class="info-value">{{ $record->raca->getLabel() }}</span>
        </div>
        @endif

        @if($record->escolaridade)
        <div class="section-group info-row">
            <span class="info-label">Escolaridade</span>
            <span class="info-value">{{ $record->escolaridade->getLabel() }}</span>
        </div>
        @endif

        @if($record->ocupacoes && count($record->ocupacoes) > 0)
        <div class="section-group info-row" title="@foreach($record->ocupacoes as $ocupacao){{ App\Ocupacao::from($ocupacao)->getLabel() }}@if(!$loop->last), @endif @endforeach">
            <span class="info-label">Ocupações</span>
            <span class="info-value">
                @foreach($record->ocupacoes as $ocupacao)
                    {{ App\Ocupacao::from($ocupacao)->getLabel() }}@if(!$loop->last), @endif
                @endforeach
            </span>
        </div>
        @endif

        <div class="section-group multiline" style="margin-top: 12px;">
            <div class="info-label">Endereço</div>
            <div class="info-value">
                {{ $record->rua ?? '' }}, {{ $record->numero ?? '' }} - {{ $record->bairro ?? '' }}<br>
                {{ $record->cidade ?? '' }} - {{ $record->estado ?? '' }}<br>
                CEP: {{ $record->cep ?? '' }}
            </div>
        </div>

        <div class="section-group multiline" style="margin-top: 12px;">
            <div class="info-label">Contatos</div>
            <div class="info-value">
                @if($record->telefone_celular)
                    Celular: {{ $record->telefone_celular }}<br>
                @endif

                @if($record->telefone_whatsapp)
                    WhatsApp: {{ $record->telefone_whatsapp }}<br>
                @endif

                @if($record->telefone_fixo)
                    Fixo: {{ $record->telefone_fixo }}<br>
                @endif

                @if($record->email)
                    Email: {{ $record->email }}
                @endif
            </div>
            @if(!$record->telefone_celular && !$record->telefone_whatsapp && !$record->telefone_fixo && !$record->email)
                <div class="info-value">Nenhum contato cadastrado</div>
            @endif
        </div>

        @if($record->tipo_deficiencia || $record->causa_deficiencia || ($record->aparelhos_utilizado && count($record->aparelhos_utilizado) > 0))
        <div class="deficiency-info">
            <div>Deficiência:</div>
            @if($record->tipo_deficiencia)Tipo: {{ $record->tipo_deficiencia->getLabel() }}<br>@endif
            @if($record->causa_deficiencia)Causa: {{ $record->causa_deficiencia->getLabel() }}<br>@endif
            @if($record->aparelhos_utilizado && count($record->aparelhos_utilizado) > 0)
            Aparelhos:
            @foreach($record->aparelhos_utilizado as $aparelho)
                {{ App\AparelhoUtilizado::from($aparelho)->getLabel() }}@if(!$loop->last), @endif
            @endforeach
            @endif
        </div>
        @endif

        @if($record->cid10 && count($record->cid10) > 0)
        <div class="section-group info-row" title="@foreach($record->cid10 as $cid){{ $cid }}@if(!$loop->last), @endif @endforeach">
            <span class="info-label">CID-10</span>
            <span class="info-value">
                @foreach($record->cid10 as $cid){{ $cid }}@if(!$loop->last), @endif @endforeach
            </span>
        </div>
        @endif

        @if($record->crm)
        <div class="section-group info-row">
            <span class="info-label">CRM</span>
            <span class="info-value">{{ $record->crm }}</span>
        </div>
        @endif
    </div>
</div>
</x-appd>
