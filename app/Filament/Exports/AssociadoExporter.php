<?php

namespace App\Filament\Exports;

use App\Models\Associado;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class AssociadoExporter extends Exporter
{
    use ExportConcerns;

    protected static ?string $model = Associado::class;

    protected static string $type = 'associados';

    private static $municipios = null;

    public static function getColumns(): array
    {
        if (! self::$municipios) {
            self::$municipios = self::getMunicipios();
        }

        return [
            ExportColumn::make('id'),
            ExportColumn::make('foto'),
            ExportColumn::make('nome'),
            ExportColumn::make('status')->state(fn ($record) => $record->status->getLabel()),
            ExportColumn::make('data_nascimento')->formatStateUsing(fn ($record) => $record->data_nascimento->format('d/m/Y')),
            ExportColumn::make('nome_social'),
            ExportColumn::make('sexo')->state(fn ($record) => $record->sexo->getLabel()),
            ExportColumn::make('declaracao_sexual')->state(fn ($record) => $record->declaracao_sexual->getLabel()),
            ExportColumn::make('cpf'),
            ExportColumn::make('rg'),
            ExportColumn::make('orgao_expedidor')->state(fn ($record) => $record->orgao_expedidor->getLabel()),
            ExportColumn::make('orgao_expedidor_uf')->state(fn ($record) => $record->orgao_expedidor_uf->getLabel()),
            ExportColumn::make('estado_civil')->state(fn ($record) => $record->estado_civil->getLabel()),
            ExportColumn::make('certidao_nascimento'),
            ExportColumn::make('naturalidade_uf')->state(fn ($record) => $record->naturalidade_uf->getLabel()),
            ExportColumn::make('naturalidade_municipio_ibge')->state(fn ($record) => self::$municipios->firstWhere('id', $record->naturalidade_municipio_ibge)['nome']),
            ExportColumn::make('mae'),
            ExportColumn::make('pai'),
            ExportColumn::make('religiao')->state(fn ($record) => $record->religiao->getLabel()),
            ExportColumn::make('ocupacoes'),
            ExportColumn::make('escolaridade')->state(fn ($record) => $record->escolaridade->getLabel()),
            ExportColumn::make('raca')->state(fn ($record) => $record->raca->getLabel()),
            ExportColumn::make('beneficios.nome'),
            ExportColumn::make('cid10.codigo'),
            ExportColumn::make('crm'),
            ExportColumn::make('causa_deficiencia')->state(fn ($record) => $record->causa_deficiencia->getLabel()),
            ExportColumn::make('tipo_deficiencia')->state(fn ($record) => $record->tipo_deficiencia->getLabel()),
            ExportColumn::make('aparelhos_utilizado'),
            ExportColumn::make('cep'),
            ExportColumn::make('rua'),
            ExportColumn::make('bairro'),
            ExportColumn::make('numero'),
            ExportColumn::make('estado'),
            ExportColumn::make('cidade'),
            ExportColumn::make('perimetro'),
            ExportColumn::make('telefone_celular'),
            ExportColumn::make('telefone_whatsapp'),
            ExportColumn::make('telefone_fixo'),
            ExportColumn::make('email'),
            ExportColumn::make('created_at')
                ->label('Data de Associação')
                ->formatStateUsing(fn ($record) => $record->created_at->format('d/m/Y H:i:s')),
            ExportColumn::make('updated_at')
                ->label('Última Atualização')
                ->formatStateUsing(fn ($record) => $record->updated_at->format('d/m/Y H:i:s')),
        ];
    }

    private static function getMunicipios(): Collection
    {
        // https://servicodados.ibge.gov.br/api/v1/localidades/municipios
        return cache()->rememberForever('municipios', function () {
            $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

            return $response->collect();
        });
    }
}
