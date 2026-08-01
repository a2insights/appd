<?php

namespace App\Filament\Exports;

use App\Models\Associado;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AssociadoExporter extends Exporter
{
    use ExportConcerns;

    protected static ?string $model = Associado::class;

    protected static string $type = 'associados';

    private static ?Collection $municipios = null;

    private static array $municipioLookup = [];

    public static function getColumns(): array
    {
        if (! self::$municipios) {
            self::$municipios = self::getMunicipios();
            self::$municipioLookup = self::$municipios
                ->mapWithKeys(fn ($municipio) => [$municipio['id'] => $municipio['nome']])
                ->all();
        }

        Log::info('Configurando colunas de exportação de associados', [
            'municipios_loaded' => self::$municipios !== null,
            'municipios_count' => self::$municipios?->count() ?? 0,
        ]);

        return [
            ExportColumn::make('id')->enabledByDefault(true),
            ExportColumn::make('foto')->enabledByDefault(false),
            ExportColumn::make('nome')->enabledByDefault(true),
            ExportColumn::make('status')->state(fn ($record) => $record->status?->getLabel())->enabledByDefault(true),
            ExportColumn::make('data_nascimento')->formatStateUsing(fn ($record) => $record->data_nascimento->format('d/m/Y'))->enabledByDefault(false),
            ExportColumn::make('nome_social')->enabledByDefault(false),
            ExportColumn::make('sexo')->state(fn ($record) => $record->sexo?->getLabel())->enabledByDefault(true),
            ExportColumn::make('declaracao_sexual')->state(fn ($record) => $record->declaracao_sexual?->getLabel())->enabledByDefault(false),
            ExportColumn::make('cpf')->enabledByDefault(false),
            ExportColumn::make('titulo_eleitor')->enabledByDefault(false),
            ExportColumn::make('rg')->enabledByDefault(false),
            ExportColumn::make('orgao_expedidor')->state(fn ($record) => $record->orgao_expedidor?->getLabel())->enabledByDefault(false),
            ExportColumn::make('orgao_expedidor_uf')->state(fn ($record) => $record->orgao_expedidor_uf?->getLabel())->enabledByDefault(false),
            ExportColumn::make('estado_civil')->state(fn ($record) => $record->estado_civil?->getLabel())->enabledByDefault(false),
            ExportColumn::make('certidao_nascimento')->enabledByDefault(false),
            ExportColumn::make('naturalidade_uf')->state(fn ($record) => $record->naturalidade_uf?->getLabel())->enabledByDefault(false),
            ExportColumn::make('naturalidade_municipio_ibge')->state(fn ($record) => self::resolveMunicipioName($record))->enabledByDefault(false),
            ExportColumn::make('mae')->enabledByDefault(false),
            ExportColumn::make('pai')->enabledByDefault(false),
            ExportColumn::make('religiao')->state(fn ($record) => $record->religiao?->getLabel())->enabledByDefault(true),
            ExportColumn::make('ocupacoes')->enabledByDefault(true),
            ExportColumn::make('escolaridade')->state(fn ($record) => $record->escolaridade?->getLabel())->enabledByDefault(true),
            ExportColumn::make('raca')->state(fn ($record) => $record->raca?->getLabel())->enabledByDefault(true),
            ExportColumn::make('beneficios.nome')->enabledByDefault(true),
            ExportColumn::make('cid10.codigo')->enabledByDefault(false),
            ExportColumn::make('crm')->enabledByDefault(false),
            ExportColumn::make('causa_deficiencia')->state(fn ($record) => $record->causa_deficiencia?->getLabel())->enabledByDefault(true),
            ExportColumn::make('tipo_deficiencia')->state(fn ($record) => $record->tipo_deficiencia?->getLabel())->enabledByDefault(true),
            ExportColumn::make('aparelhos_utilizado')->enabledByDefault(true),
            ExportColumn::make('cep')->enabledByDefault(false),
            ExportColumn::make('rua')->enabledByDefault(false),
            ExportColumn::make('bairro')->enabledByDefault(false),
            ExportColumn::make('numero')->enabledByDefault(false),
            ExportColumn::make('estado')->enabledByDefault(false),
            ExportColumn::make('cidade')->enabledByDefault(false),
            ExportColumn::make('perimetro')->enabledByDefault(false),
            ExportColumn::make('telefone_celular')->enabledByDefault(false),
            ExportColumn::make('telefone_whatsapp')->enabledByDefault(false),
            ExportColumn::make('telefone_fixo')->enabledByDefault(false),
            ExportColumn::make('email')->enabledByDefault(false),
            ExportColumn::make('idade')
                ->label('Idade')
                ->formatStateUsing(fn ($record) => Carbon::parse($record->data_nascimento)->age)
                ->enabledByDefault(true),
            ExportColumn::make('created_at')
                ->label('Data de Associação')
                ->formatStateUsing(fn ($record) => $record->created_at->format('d/m/Y H:i:s'))->enabledByDefault(true),
            ExportColumn::make('last_renewal_at')
                ->formatStateUsing(fn ($record) => $record->carteirinhas->first()?->data_emissao?->format('d/m/Y'))
                ->label('Última Renovação')
                ->enabledByDefault(true),
            ExportColumn::make('updated_at')
                ->label('Última Atualização')
                ->formatStateUsing(fn ($record) => $record->updated_at->format('d/m/Y H:i:s'))->enabledByDefault(true),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        Log::info('Aplicando eager loading para exportação de associados', [
            'relations' => ['beneficios', 'carteirinhas', 'cid10'],
        ]);

        return $query->with([
            'beneficios',
            'carteirinhas' => fn ($relation) => $relation->latest()->limit(1),
            'cid10',
        ]);
    }

    private static function resolveMunicipioName($record): ?string
    {
        try {
            if (! $record->naturalidade_municipio_ibge) {
                return null;
            }

            if (! isset(self::$municipioLookup[$record->naturalidade_municipio_ibge])) {
                Log::warning('Município não encontrado no lookup da exportação', [
                    'associado_id' => $record->id,
                    'municipio_id' => $record->naturalidade_municipio_ibge,
                ]);

                return null;
            }

            return self::$municipioLookup[$record->naturalidade_municipio_ibge];
        } catch (\Throwable $e) {
            Log::warning('Erro ao resolver município para exportação', [
                'associado_id' => $record->id ?? null,
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private static function getMunicipios(): Collection
    {
        // https://servicodados.ibge.gov.br/api/v1/localidades/municipios
        return cache()->rememberForever('municipios', function () {
            try {
                $response = Http::timeout(10)->get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

                if ($response->failed()) {
                    Log::warning('Falha ao buscar municípios do IBGE para exportação', [
                        'status' => $response->status(),
                    ]);

                    return collect();
                }

                return $response->collect();
            } catch (\Throwable $e) {
                Log::warning('Erro ao buscar municípios do IBGE para exportação', [
                    'exception' => $e->getMessage(),
                ]);

                return collect();
            }
        });
    }
}
